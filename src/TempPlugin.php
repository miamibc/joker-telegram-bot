<?php
/**
 * Joker Hello Plugin
 *
 * Say /start or hello to the bot in private chat
 * Send sticker, it answers with random sticker from same sticker pack
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class TempPlugin extends Plugin
{

  const API_URL = 'http://api.openweathermap.org/data/2.5/weather?q={PLACE}&units=metric&APPID={APIKEY}';

  public function onTextMessage( Event $event )
  {

    if (!preg_match('@^(/temp|!temp)\s+([\w]+)$@ui', $event->getMessageText(), $matches)) return;

    if (!$api_key = $this->getOption('api_key'))
    {
      $event->answerMessage("!temp: Openweather API key is required for this plugin");
    }

    $url = strtr( self::API_URL, [
      '{PLACE}'  => $matches[2],
      '{APIKEY}' => $api_key,
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

    /*
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

    $celsius = round( $data['main']['temp'] , 1);
    $result = [ "{$celsius}°C" ];

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