<?php
/**
 * Whynot plugin for Joker made with help of goody2.ai
 * Generates otmazki why not ...
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Whynot extends Base
{

  protected $options = [
    'description' => 'Generates otmazki why not ...',
    'risk' => 'MEDIUM. Nothing is stored in plugin, no information about requester is sent to API. Visit author site for privacy information https://brain.wtf/',
  ];

  public function onPublicText( Update $update )
  {

    $text = $update->message()->text();
    if ($text->trigger() !== 'whynot') return;

    $reply = $this->_request('https://www.goody2.ai/send', [
      "message"=> $text->token(1),
      "debugParams"=>null,
    ]);

    $update->replyMessage( $reply );
    return false;
  }


  /**
   * Perform request to Twitch API
   * @param string $method
   * @param string $url
   * @param array $params
   *
   * @return mixed
   */
  private function _request( string $url = 'https://www.goody2.ai/send', $data = null, array $headers = ['Content-type: application/json'] )
  {
    $json = file_get_contents($url, false, stream_context_create(["http" => [
      "method" => 'POST',
      "header" => implode("\r\n", $headers),
      "content" => json_encode($data),
    ]]));

    /* here we have mix of JSON with additional data, events like in Mastodon
       event: message
       data: {"content":""}

       event: message
       data: {"content":"As"}

       event: message
       data: {"content":" an"}

       event: message
       data: {"content":" AI"}
    */

    $result = "";
    foreach (explode("\n", $json ) as $k => $v )
    {
      if ($k%3 == 1) // get second of 3 lines (1 = 2 null-based)
      {
        $line = json_decode(substr( $v, strpos($v, ": ")+2), true);
        $this->bot->log($line);
        $result .= $line["content"] ?? '';
      }
    }
    return $result;
  }


}