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

class TempPlugin extends Plugin
{

  const API_URL = 'http://api.openweathermap.org/data/2.5/weather?q={PLACE}&units=metric&APPID={APIKEY}';

  public function onPublicText( Event $event )
  {

    if (!$api_key = $this->getOption('api_key'))
    {
      $event->answerMessage("!temp: Openweather API key is required for this plugin");
      return false;
    }

    $text = $event->getMessageText();

    if (!preg_match('@^(/temp|!temp)\b@ui', $text)) return;

    if (!preg_match('@^(/temp|!temp)\s+(.*)$@ui', $text, $matches))
    {
      $place = "Tallinn";
    }
    else
    {
      $place = trim($matches[2]);
    }

    $url = strtr( self::API_URL, [
      '{PLACE}'  => urlencode( $place ),
      '{APIKEY}' => urlencode( $api_key ),
    ]);

    if (!$json = @file_get_contents( $url ))
    {
      $event->answerMessage( "!temp: oops... can't find thermometer there :/" );
      return false;
    }

    $data = json_decode( $json , true );

    if (!isset( $data['main']['temp'], $data['name'] ))
    {
      $event->answerMessage( "!temp: uh... sorry, thermometer is broken there" );
      return false;
    }

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

    $message = "!temp: ". trim( implode(', ', $result) ) . " in {$data['name']} {$data['sys']['country']}";

    $event->answerMessage( $message );
    return false;
  }

}