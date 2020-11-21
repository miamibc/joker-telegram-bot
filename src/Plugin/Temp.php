<?php
/**
 * Joker Temp Plugin
 *
 * Ask current temperature in city, for example:
 *   !temp moscow
 *   !temp 59.4525804,24.844022
 * bot will answer:
 *   !temp: -6.8°C, from -10 to -4.44°С, wind 1 m/s, clouds 100%, pressure 1033 hPa, visibility 10000 m, overcast clouds in Moscow RU
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class Temp extends Plugin
{

  const API_URL = 'http://api.openweathermap.org/data/2.5/weather';

  private $last_query = [];

  public function onPublicText( Event $event )
  {

    if (!$api_key = $this->getOption('api_key'))
    {
      $event->answerMessage("!temp: Openweather API key is required for this plugin (api_key)");
      return false;
    }

    $text = $event->getMessageText();
    $author = $event->getMessageFromId();

    if (!preg_match('@^(/temp|!temp)\b(.*)?$@ui', $text, $matches)) return;

    $trigger = mb_strtolower( trim( $matches[1] ));
    $query   = mb_strtolower( trim( $matches[2] ));

    // if no query, try to recall last one or get default
    if (!$query)
    {
      $query = isset($this->last_query[$author])
             ? $this->last_query[$author]
             : $this->getOption('default', 'tallinn');
    }

    // virtual locations
    $locations = [
      'королевство' => 'narva',                // requested by Overdoze
      'korolevstvo' => 'narva',
      'kingdom'     => 'narva',
      'tll'         => 'tallinn',              // home town
      'lasnamae'    => '59.4525804,24.844022', // home district
      'spb'         => 'sankt-peterburg',      // best town
      'msk'         => 'moscow',               // big town
      'nowhere'     => '60.4600098,169.5706892',
    ];
    if (isset($locations[$query]))
    {
      $query = $locations[$query];
    }

    // coordinates,or place name?
    if ( preg_match('@^(-?[\d.]+)[, ]+(-?[\d.]+)$@', $query, $matches) )
    {
      $params = [
        'lat'   => $matches[1],
        'lon'   => $matches[2],
        'units' => 'metric',
        'APPID' => $this->getOption('api_key'),
      ];
    }
    else
    {
      $params = [
        'q'     => $query,
        'units' => 'metric',
        'APPID' => $this->getOption('api_key'),
      ];
    }

    $url = self::API_URL . '?' . http_build_query( $params );

    if (!$json = @file_get_contents( $url ))
    {
      $event->answerMessage( "$trigger: oops... can't find thermometer there :/" );
      return false;
    }

    $data = json_decode( $json , true );

    if (!isset( $data['main']['temp'], $data['name'] ))
    {
      $event->answerMessage( "$trigger: uh... sorry, thermometer is broken there" );
      return false;
    }

    // got result! remember last query
    $this->last_query[$author] = $query;

    /* example
      {
        "coord": { "lon": 139,"lat": 35},
        "weather": [
          {
            "id": 800,
            "main": "Clear",
            "description": "clear sky",
            "icon": "01n"
          }
        ],
        "base": "stations",
        "main": {
          "temp": 289.92,
          "pressure": 1009,
          "humidity": 92,
          "temp_min": 288.71,
          "temp_max": 290.93
        },
        "wind": {
          "speed": 0.47,
          "deg": 107.538
        },
        "clouds": {
          "all": 2
        },
        "dt": 1560350192,
        "sys": {
          "type": 3,
          "id": 2019346,
          "message": 0.0065,
          "country": "JP",
          "sunrise": 1560281377,
          "sunset": 1560333478
        },
        "timezone": 32400,
        "id": 1851632,
        "name": "Shuzenji",
        "cod": 200
      }
     */

    $result = [ round( $data['main']['temp'] , 1) . "°C" ];

    if (isset($data['main']['temp_min'], $data['main']['temp_max']) && $data['main']['temp_min'] !== $data['main']['temp_max'])
      $result[] = "from {$data['main']['temp_min']} to {$data['main']['temp_max']}°С";

    if (isset($data['wind']))
      $result[] = "wind {$data['wind']['speed']} m/s";

    if (isset($data['clouds']['all']))
      $result[] = "clouds {$data['clouds']['all']}%";

    if (isset($data['main']['pressure']))
      $result[] = "pressure {$data['main']['pressure']} hPa";

    if (isset($data['visibility']))
      $result[] = "visibility {$data['visibility']} m";

    foreach ( $data['weather'] as $w)
      $result[] = $w['description'];

    $place = isset($data['name']) && $data['name']
      ? "{$data['name']}, {$data['sys']['country']}"
      : "this place";

    $message = "$trigger: ". trim( implode(', ', $result) ) . " in {$place}";

    $event->answerMessage( $message );
    return false;
  }

}