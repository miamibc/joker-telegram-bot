<?php

/**
 * Corona virus stats for Joker
 *
 * You can:
 * Ask last report by providing country and region
 *   !corona Estonia
 *   !corona Berlin, Germany
 *
 * Constructor $options
 *   file         - (required) file name where to same last csv from github
 *   update_hours - (optional, default 3) how often to update information from github
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class Corona extends Plugin
{

  const LIST_URL = "https://api.github.com/repos/CSSEGISandData/COVID-19/contents/csse_covid_19_data/csse_covid_19_daily_reports?ref=master";

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/corona|!corona)\b(.*)?$@ui', $text, $matches)) return;

    $trigger = trim( $matches[1] );
    $query   = trim( $matches[2] );

    if (empty( $query ))
    {
      $event->answerMessage("Usage: $trigger country, region\n\n$trigger Estonia\n$trigger Berlin, Germany");
      return false;
    }

    // if no data file set, stop plugin
    if (!$filename = $this->getOption('file')) return;

    // if no data file, or modified 3 hours ago ago, re-download file
    if (!file_exists($filename)
        || time() - filemtime($filename) > $this->getOption( 'update_hours', 3 )*60*60)
          $this->download_csse_covid_19_data();

    // search in data file
    if (!$result = $this->search_csse_covid_19_data($query))
    {
      $event->answerMessage("Can't find corona data for $query, please try with country name");
      return false;
    }

    /*
      'FIPS' => ''
      'Admin2' => ''
      'Province_State' => ''
      'Country_Region' => 'Estonia'
      'Last_Update' => '2020-11-20 05:26:28'
      'Lat' => '58.5953'
      'Long_' => '25.0136'
      'Confirmed' => '8715'
      'Deaths' => '86'
      'Recovered' => '5264'
      'Active' => '3365'
      'Combined_Key' => 'Estonia'
      'Incident_Rate' => '656.9727689875683'
      'Case_Fatality_Ratio' => '0.9868043602983362'
    */

    // if array is not associative, this is suggestions
    if (isset($result[0]))
    {
      $event->answerMessage("Try $trigger with more specific query:\n" . implode("\n", $result) );
      return false;
    }

    // answer with result, don't process other plugins
    $event->answerMessage(
      "Corona situation in $result[Combined_Key]:\n" .
      "Incident rate: $result[Incident_Rate]\n" .
      "Case fatality ratio: $result[Case_Fatality_Ratio]\n" .
      "Active cases: $result[Active]\n" .
      "Confirmed cases: $result[Confirmed]\n" .
      "Recovered cases: $result[Recovered]\n" .
      "Deaths: $result[Deaths]\n" .
      "Last update: $result[Last_Update]"
    );
    return false;
  }

  public function download_csse_covid_19_data()
  {
    // if no filename, nothing to download
    if (!$filename = $this->getOption('file')) return false;

    $opts = array(
      'http'=>array(
        'method'=>"GET",
        'header'=>"Accept: application/vnd.github.v3+json\r\n" .
                  "User-Agent: Mozilla/5.0 (compatible; Joker/1.0; +https://github.com/miamibc/joker-telegram-bot)\r\n"
      )
    );
    $context = stream_context_create($opts);
    if (!$content = file_get_contents( self::LIST_URL , false, $context)) return false;
    if (!$list = json_decode( $content, true)) return false;

    /* here we have array of items, like this
    {
      "name": "01-22-2020.csv",
      "path": "csse_covid_19_data/csse_covid_19_daily_reports/01-22-2020.csv",
      "sha": "26a4512a85668bebac38522fe6579ccb05a434c3",
      "size": 1675,
      "url": "https://api.github.com/repos/CSSEGISandData/COVID-19/contents/csse_covid_19_data/csse_covid_19_daily_reports/01-22-2020.csv?ref=master",
      "html_url": "https://github.com/CSSEGISandData/COVID-19/blob/master/csse_covid_19_data/csse_covid_19_daily_reports/01-22-2020.csv",
      "git_url": "https://api.github.com/repos/CSSEGISandData/COVID-19/git/blobs/26a4512a85668bebac38522fe6579ccb05a434c3",
      "download_url": "https://raw.githubusercontent.com/CSSEGISandData/COVID-19/master/csse_covid_19_data/csse_covid_19_daily_reports/01-22-2020.csv",
      "type": "file",
      "_links": {
        "self": "https://api.github.com/repos/CSSEGISandData/COVID-19/contents/csse_covid_19_data/csse_covid_19_daily_reports/01-22-2020.csv?ref=master",
        "git": "https://api.github.com/repos/CSSEGISandData/COVID-19/git/blobs/26a4512a85668bebac38522fe6579ccb05a434c3",
        "html": "https://github.com/CSSEGISandData/COVID-19/blob/master/csse_covid_19_data/csse_covid_19_daily_reports/01-22-2020.csv"
      }
    }
    */

    $result = [];
    foreach ($list as $item)
    {
      if ($item['type'] != 'file') continue;
      if (substr( $item['name'], -4) !== '.csv') continue;
      $name = substr( $item['name'], 0, -4 );
      $parts = explode( '-', $name);
      $result[ $parts[2] . $parts[0] . $parts[1] ] = $item;
    }

    // sort by key (date from filename)
    ksort($result);

    // get last element
    $last = array_pop($result);

    // download csv
    if (!$content = file_get_contents( $last['download_url'], false, $context)) return  false;

    // create directory, if not exists
    if (!file_exists(dirname($filename)))
      mkdir(dirname($filename), 0777, true);

    // save csv
    file_put_contents( $filename , $content);

    return true;
  }

  public function search_csse_covid_19_data($query)
  {

    if (!$filename = $this->getOption('file')) return false;
    if (!file_exists($filename)) return false;

    $query = strtolower(trim($query));

    $handle = fopen($filename, 'r');
    $header = fgetcsv($handle);
    $suggestions = [];
    while(($row = fgetcsv($handle))!== false)
    {
      $row = array_combine($header, $row);
      $key = strtolower( trim( $row['Combined_Key'] ));

      // exact match, answer with data
      if ( $key === $query)
      {
        fclose($handle);
        return $row;
      }

      // not exact match found, make a suggestion
      if (stripos($key, $query) !== false)
        $suggestions[] = $row['Combined_Key'];

    }

    fclose($handle);

    return $suggestions;
  }
}