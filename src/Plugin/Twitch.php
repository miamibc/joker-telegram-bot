<?php
/**
 * Twitch Plugin for Joker
 *
 * Search channels
 *   !twitch quake
 *   !twitch poker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Carbon\Carbon;
use Joker\Helper\Strings;
use Joker\Parser\Update;

class Twitch extends Base
{

  protected $options = [
    'description' => 'Twitch search',
    'risk' => 'MEDIUM. Nothing is stored in plugin, no information about requester is sent to Twitch API. Twitch API privacy poilcy https://www.twitch.tv/p/ru-ru/legal/developer-agreement/',
  ];

  protected $access_token, $expires_at;

  public function onPublicText( Update $update )
  {
    $text = $update->message()->text();
    if ($text->trigger() !== 'twitch') return;

    if (empty($text = trim( $text->token(1, null) )))
    {
      $update->answerMessage('Usage: !twitch searchtext');
      return false;
    }

    ($result = $this->searchChannels($text))
      ? $update->answerMessage( $result, ['parse_mode' =>'HTML','disable_web_page_preview' =>true] )
      : $update->answerMessage('Nothing found :(')
    ;

    return false;

  }

  /**
   * Search channels
   *
   * @param $query
   *
   * @return false|string
   * @throws \Exception
   */
  public function searchChannels( $query )
  {
    $array = $this->_request('GET', '/helix/search/channels', ['query'=>$query]);
    if (!isset($array['data'])) return false;

    $result = [];
    foreach ($array['data'] as $item)
    {
      // if (count($result) > 10) break;
      $result[] =  trim( implode( ' ', [
        // online/offline
        $item['is_live'] ?'🌕':'🌑',
        // title and a link
        "<a href=\"https://www.twitch.tv/{$item['display_name']}\">" . trim($item['title']) . "</a>",
        // started time
        $item['started_at'] ? ' started '. Carbon::parse($item['started_at'])->diffForHumans(['short' => true, 'parts' => 3]) : '' ,
      ]));
    }

    return implode( PHP_EOL, $result);
  }

  /**
   * Performs authorization
   * @return bool
   */
  private function _authorize()
  {
    $json = file_get_contents("https://id.twitch.tv/oauth2/token?". http_build_query([
      'client_id' => $this->getOption('client_id', getenv('TWITCH_CLIENT_ID')),
      'client_secret' => $this->getOption('client_secret', getenv('TWITCH_CLIENT_SECRET')),
      'grant_type' => 'client_credentials',
    ]), false, stream_context_create(["http" => [
      "method" => 'POST',
    ]]));

    $data = json_decode( $json, true);
    if (!isset($data['access_token'], $data['expires_in'])) return false;

    $this->access_token = $data['access_token'];
    $this->expires_at = time() + $data['expires_in'];

    return true;

  }

  /**
   * Perform request to Twitch API
   * @param string $method
   * @param string $url
   * @param array $params
   *
   * @return mixed
   */
  private function _request( string $method = 'GET', string $url = '/', array $params = [])
  {

    // authorize, if necessary
    if (empty( $this->access_token ) || time() > $this->expires_at)
    {
      $this->_authorize();
    }

    $json = file_get_contents("https://api.twitch.tv$url?". http_build_query($params), false, stream_context_create(["http" => [
      "method" => $method,
      "header" => "Client-id: ". $this->getOption('client_id', getenv('TWITCH_CLIENT_ID'))."\r\n" .
                  "Authorization: Bearer ". $this->access_token."\r\n"
    ]]));

    return json_decode( $json, true );
  }

}