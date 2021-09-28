<?php
/**
 * Youtube plugin for Joker
 *
 * Posts audiotrack from Youtube video.
 * Youtube-dl (http://ytdl-org.github.io/youtube-dl/download.html is required to make this plugin work as planned, if absent you'll see link to youtube video instead.
 *
 * Options:
 * - `api_key` string, optional, default from env variable GOOGLE_API_KEY - Google API key with Youtube API v3 enabled.
 * - `dir` string, optional, default data/ytmusic - directory to save mp3 files to
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use GuzzleHttp\Client;

class Ytmusic extends Base
{

  protected $options = [
    'dir' => 'data/ytmusic',
  ];

  public function onPublicText( Update $update )
  {

    if ($update->message()->text()->trigger() !== 'ytmusic') return;

    $client = new Client();
    $query = $update->message()->text()->token(1);
    $url = ($videoId = self::linkToYoutube($query))
      ? "https://www.googleapis.com/youtube/v3/videos?".http_build_query([
        'id'            => $videoId,
        'part'          => 'snippet',
        'key'           => $this->getOption('api_key',getenv('GOOGLE_API_KEY')),
      ])
      : "https://www.googleapis.com/youtube/v3/search?".http_build_query([
        'q'             => $query,
        'part'          => 'snippet',
        'type'          => 'video',
        // 'videoDuration' => 'short',
        'order'         => 'viewCount',
        'key'           => $this->getOption('api_key',getenv('GOOGLE_API_KEY')),
      ]);

    $result = $client->get($url);
    $array = json_decode($result->getBody(),true);

    // create directory for downloading files
    $dir = $this->getOption('dir');
    if (!file_exists($dir)) mkdir($dir);

    // iterate results
    foreach ($array['items'] as $video)
    {
      $videoId  = $video['id']['videoId'] ?? $video['id']; // search has id.videoId, videos has id
      $url      = "https://youtu.be/$videoId";
      $title    = html_entity_decode($video['snippet']['title']);
      $slug     = self::slugify($title);
      $filename = "$dir/$slug.mp3";

      // download with youtuube-dl
      if (!file_exists($filename))
      {
        $update->customRequest('sendChatAction',[
          'chat_id' => $update->message()->chat()->id(),
          'action'  => 'record_voice',
        ]);
        `youtube-dl --ignore-errors --extract-audio --audio-format mp3 '$url' -o '$filename'`;
      }

      // send file, if downloaded and size less than 50 megabytes
      if (file_exists($filename) && filesize($filename) < 50 * 1024 * 1024)
      {
        $update->bot()->sendAudio($update->message()->chat()->id(),$filename,[
          'title'   => $title,
          'caption' => "Watch ➝ $url",
        ]);
        return false;
      }

    }

    $update->answerMessage('Nothing found :(');
    return false;

  }

  public static function linkToYoutube( $query )
  {
    return preg_match('@(?:youtu\.be/|youtube\.com/watch\?v=)([^"&?\/\s]+)$@uim', $query, $matches) ? $matches[1] : false;
  }

  public static function slugify($text, string $divider = '-')
  {
    // convert cyrillic chars to latin
    $text = transliterator_transliterate('Russian-Latin/BGN', $text );

    // replace non letter or digits by divider
    $text = preg_replace('~[^\pL\d]+~u', $divider, $text);

    // transliterate
    $text = iconv('utf-8', 'us-ascii//TRANSLIT', $text);

    // remove unwanted characters
    $text = preg_replace('~[^-\w]+~', '', $text);

    // trim
    $text = trim($text, $divider);

    // remove duplicate divider
    $text = preg_replace('~-+~', $divider, $text);

    // lowercase
    $text = strtolower($text);

    if (empty($text)) {
      return 'n-a';
    }

    return $text;
  }
}