<?php
/**
 * Joker Temp Plugin
 *
 * Ask current temperature in city, for example:
 *   !temp moscow
 * bot will answer:
 *   !temp: -6.8°C, from -10 to -4.44°С, wind 1 m/s, clouds 100%, pressure 1033 hPa, visibility 10000 m, overcast clouds in Moscow RU
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class RandomPlugin extends Plugin
{

  const API_URL = 'https://randomall.ru/api/custom/gen/{TOPIC}/';

  const TOPICS  = [
    'sluh' => 112,
    'mem' => 108,
    'baba' => 71,
    'fobija' => 126,
    'banda' => 111,
    'shedevr' => 58,
    'rabota' => 75,
    'mania' => 92,
    // 'hobby' => 113,
    'predpochtenie' => 85,
    'location' => 57,
    'animal' => 137,
    'person' => 84,
    'fantastika' => 72,
    'proishestvie' => 83,
    'pirat' => 121,
    'parent' => 123,
    'planeta' => 66,
    // 'weapon' => 117,
    'operation' => 169,
    'story' => 106,
    'gosudarstvo' => 152,
    'lovestory' => 196,
    'currency' => 170,
    'bolezn' => 173,
    'risunok' => 278,
    'speech' => 226,
    'flag' => 265,
    'reklama' => 190,
    'skorogovorka' => 210,
  ];

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/random|!random)\b@ui', $text)) return;

    if (!preg_match('@^(/random|!random)\s+(\w+)$@ui', $text, $matches))
    {
      $event->answerMessage("Usage: !random topic, where topic can be: ". implode(" ", array_keys( self::TOPICS)));
      return false;
    }

    $topic = trim($matches[2]);
    $topicid = strtr( $topic, self::TOPICS);

    $url = strtr( self::API_URL, [
      '{TOPIC}'  => urlencode( $topicid ),
    ]);

    if (!$json = @file_get_contents( $url ))
    {
      $event->answerMessage( "!random: cannot generate $topic :p" );
      return false;
    }

    $data = json_decode( $json , true );

    if (!isset( $data['text'] ))
    {
      $event->answerMessage( "!random: cannot generate $topic :p" );
      return false;
    }

    /* example
{"text":"В последнее время какие-то люди постоянно посещают центральный городской магазин по ночам."}
     */

    $message = "!random $topic: $data[text]";

    $event->answerMessage( $message );
    return false;
  }

}