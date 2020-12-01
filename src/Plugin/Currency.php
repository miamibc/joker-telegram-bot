<?php

/**
 * Currency exchange rates for Joker
 * @see https://developers.coinbase.com/api/v2#get-currencies
 *
 * You can:
 * Ask last report by providing country and region
 *   !currency BTC USD
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class Currency extends Plugin
{

  const RATE_URL = "https://api.coinbase.com/v2/exchange-rates";

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (preg_match('@^(/currency|!currency)$@ui', $text, $matches))
    {
      $trigger = trim( $matches[1] );
      $event->answerMessage("Usage: $trigger [from] [to]\nExample: $trigger USD BTC");
      return false;
    }

    if (!preg_match('@^(/currency|!currency) (\w+) (\w+)$@ui', $text, $matches)) return;

    $trigger = trim( $matches[1] );
    $from    = strtoupper( trim( $matches[2] ) );
    $to      = strtoupper( trim( $matches[3] ) );

    $result = $this->request($from,$to);

    $event->answerMessage( $result ? "1 $from = $result $to" : "Can't find exchange rate from $from to $to");
    return false;
  }

  public function request( $from, $to )
  {
    $context = stream_context_create([
      'http'=> [
        'method'=>"GET",
        'header'=>"Accept: application/vnd.github.v3+json\r\n" .
                  "User-Agent: Mozilla/5.0 (compatible; Joker/1.0; +https://github.com/miamibc/joker-telegram-bot)\r\n"
      ]
    ]);
    $url = self::RATE_URL . '?' . http_build_query(['currency'=>$from]);
    if (!$content = file_get_contents( $url , false, $context)) return false;
    if (!$result = json_decode( $content, true)) return false;

    /*
      {
        "data": {
          "currency": "BTC",
          "rates": {
            "AED": "36.73",
            "AFN": "589.50",
            "ALL": "1258.82",
            "AMD": "4769.49",
            "ANG": "17.88",
            "AOA": "1102.76",
            "ARS": "90.37",
            "AUD": "12.93",
            "AWG": "17.93",
            "AZN": "10.48",
            "BAM": "17.38",
            ...
          }
        }
      }
    */

    return isset($result['data']['rates'][$to]) ? $result['data']['rates'][$to] : false;
  }

}