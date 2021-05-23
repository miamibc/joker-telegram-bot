<?php
/**
 * Lurkmore for Joker
 *
 * Ask random joke, or search by id or text:
 *   !lurk Вы так говорите, будто это что-то плохое
 *
 * Bot will show you first pharagraph of a article
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class Lurk extends Plugin
{

  const API_URL  = 'https://lurkmore.to/api.php';

  public function onPublicText( Event $event )
  {

    $trigger =  $event->message()->text()->trigger();
    if ($trigger !== 'lurk') return;

    $query = $event->message()->text()->token(1);

    if (empty( $query ))
    {
      $event->answerMessage("!$trigger usage: !$trigger topic");
      return false;
    }

    // search
    $results = $this->doSearch( $query );

    if (!in_array($query, $results))
    {
      foreach ($results as $i => $result)
        $results[$i] = "$trigger $result";

      $event->answerMessage("Please choose one:\n".implode("\n",$results));
      return false;
    }

    $page = $this->doParse( $query );
    $event->answerMessage( $page );
    return false;
  }

  private function doSearch($query)
  {
    $url = self::API_URL . '?' . http_build_query([
      'format'  => 'json',
      'list'    => 'search',
      'action'  => 'query',
      'srsearch'=> $query,
    ]);

    if (!$content = @file_get_contents( $url ))  return;
    if (!$data    = json_decode($content, true)) return;
    if (!isset( $data['query']['search']) )      return;

    $result = [];
    foreach ( $data['query']['search'] as $item )
    {
      $result[] = $item['title'];
    }

    return $result;

  }

  /**
   * @param $query
   *
   * @see https://lurkmore.to/api.php?action=parse&page=%D0%91%D0%BE%D1%82&prop=text&format=json
   * @return string|void
   */
  private function doParse( $query )
  {
    $url = self::API_URL . '?' . http_build_query([
        'format'  => 'json',
        'action'  => 'parse',
        'page'    => $query,
        'prop'    => 'text',
      ]);

    if (!$content = @file_get_contents( $url ))  return;
    if (!$data    = json_decode($content, true)) return;
    if (!isset( $data['parse']['text']['*']) )      return;

    $converter = new \League\HTMLToMarkdown\HtmlConverter(['strip_tags' => true ]);
    $markdown  = $converter->convert( $data['parse']['text']['*'] );

    return self::HtmlToBrief( $markdown, 1000, '...' );

  }

  public static function HtmlToBrief($html, $limit = 1000, $suffix = "...")
  {
    $value = strip_tags($html); // clean tags
    // $value = preg_replace('@\s+@m', ' ', $value ); // remove whitespaces and newlines
    $value = trim($value);
    $value = wordwrap( $value , $limit, '<!-- cut here -->' ); // cut
    $pos  = strpos($value, '<!-- cut here -->');
    return $pos === false ? $value : substr($value, 0, $pos). $suffix;
  }

}