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

  const API_URL = 'https://api.spotify.com/v1';

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/spotify|!spotify|/mp4|!mp4)\b(.*)?$@ui', $text, $matches)) return;

    $trigger = trim( $matches[1] );

    if (! $q = trim($matches[2]) )
    {
      $q = $this->randomSearch();
    }

    $result = $this->_get("/search", ['type'=>'track','q'=>$q ]);

    if (!isset( $result['tracks']['items']) )
    {
      $event->answerMessage("Nothing found :(");
      return false;
    }

    $track = $result['tracks']['items'][ mt_rand(0, count( $result['tracks']['items'] )-1) ];

    $description = $this->getTrackDescription( $track );

    $event->answerMessage( $trigger . ": " . $description );
    return false;

  }

  private function randomSearch( $length = 2)
  {
    $chars = "abcdefghijklmnopqrstuvwxyz";
    $result = '%';
    for($i=0; $i<$length; $i++)
      $result .= $chars[mt_rand(0, strlen($chars) - 1)] . '%';
    return $result;
  }

  private function _get( $command, $params )
  {
    $token = $this->getOption("token");

    $query    = http_build_query( $params );
    $result   = file_get_contents( self::API_URL . $command . '?' . $query, false, stream_context_create([
      'http'=> [
        'method'=>"GET",
        'header'=>"Authorization: Bearer $token\r\n"
      ]
    ]));
    return json_decode($result, true);
  }

  private function getTrackDescription( $element )
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
      $preview_url = "Can't find link, search by yourself :p";

    return "Listen to '$element[name]' by $artists → $preview_url";
  }
}