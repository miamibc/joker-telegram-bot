<?php
/**
 * Random music track from Spotify, an API Plugin for Joker
 *
 * Ask random track or search:
 *   !spotify
 *   !spotify limp bizkit
 *
 * Bot will answer with random track from the top of results.
 *
 * Spotify documentation https://developer.spotify.com/documentation/web-api/reference-beta/#category-search
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class SpotifyPlugin extends Plugin
{

  const SPOTIFY_API_ENDPOINT  = 'https://api.spotify.com/v1';
  const SPOTIFY_AUTH_ENDPOINT = 'https://accounts.spotify.com/api/token';
  private $token, $token_expire_time;

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/spotify|!spotify|/mp4|!mp4)\b(.*)?$@ui', $text, $matches)) return;

    $trigger = trim( $matches[1] );

    // if query is empty, generate random query
    if (! $query = trim($matches[2]) )
    {
      $query = $this->randomQuery();
    }

    // if not authorized, do it
    if ( empty( $this->token) || time() > $this->token_expire_time)
    {
      $this->doAuthorize();
    }

    // perform search
    $result = $this->doSearch($query);
    if (!isset( $result['tracks']['items']) )
    {
      $event->answerMessage("$trigger: Nothing found :( Let's try again?");
      return false;
    }

    // get random track, extract information and answer
    $track  = $result['tracks']['items'][ mt_rand(0, count( $result['tracks']['items'] )-1) ];
    $answer = $this->getTrackInformation( $track );
    $event->answerMessage( "$trigger: $answer" );
    return false;

  }

  /**
   * Build random query for search API, example '%a%b%'
   * @param int $length number of letters
   *
   * @return string
   */
  private function randomQuery($length = 2)
  {
    $chars = "abcdefghijklmnopqrstuvwxyz";
    $result = '%';
    for($i=0; $i<$length; $i++)
    {
      $result .= $chars[mt_rand(0,strlen($chars) - 1)].'%';
    }
    return $result;
  }

  /**
   * Authorzie in Spotify API, get temporary api token
   * https://developer.spotify.com/documentation/general/guides/authorization-guide/#client-credentials-flow
   * @return bool Success status
   */
  private function doAuthorize()
  {
    $authorization  = base64_encode( $this->getOption('client_id') . ':' . $this->getOption('secret'));
    $result = file_get_contents( self::SPOTIFY_AUTH_ENDPOINT, false, stream_context_create([
      'http'=> [
        'method'=>"POST",
        'content'=> $content = http_build_query(["grant_type"=>"client_credentials"]),
        'header'=>"Authorization: Basic $authorization\r\n" .
                  "Content-Type: application/x-www-form-urlencoded\r\n" .
                  "Content-Length: ".strlen($content)."\r\n" .
                  "",
      ]
    ]));
    $result = json_decode( $result, true );
    if (!isset($result['access_token'])) return false;
    $this->token = $result['access_token'];
    $this->token_expire_time = time() + $result['expires_in'];
    return true;

  }

  /**
   * Search Spotify API for track with given query
   * @param $query
   *
   * @return array
   */
  private function doSearch( $query )
  {
    $query  = http_build_query( ['type'=>'track','q'=>$query ] );
    $result = file_get_contents(self::SPOTIFY_API_ENDPOINT."/search?$query", false, stream_context_create([
      'http'=> [
        'method'=>"GET",
        'header'=>"Authorization: Bearer $this->token\r\n"
      ]
    ]));
    return json_decode($result, true);
  }


  private function getTrackInformation($element )
  {
    $artists = [];
    foreach ($element['artists'] as $artist)
      $artists[] = $artist['name'];
    $artists = implode( ' & ', $artists);

    if     (isset($element['preview_url']))
      $preview_url = $element['preview_url'];
    elseif (isset($element['external_urls']['spotify']))
      $preview_url = $element['external_urls']['spotify'];
    else
      $preview_url = "No link, search by yourself :p";

    return "Listen to $element[name] by $artists → $preview_url";
  }
}