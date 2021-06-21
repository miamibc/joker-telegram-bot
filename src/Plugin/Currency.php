<?php

/**
 * Currency exchange rates for Joker (thanks ʎǝxǝl∀ for ide∀)
 * @see https://developers.coinbase.com/api/v2 Coinbase API documentation
 *
 * You can ask bot for currency exchange rate
 *   !currency BTC USD
 * And receive something like
 *   1 BTC = 19354.425 USD
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

    $text = $event->message()->text();

    // trigger without parameters, show help message
    if ($text->trigger() !== 'currency') return;

    // token 1 and 2 required. If not set, answer with help message
    if (!$text->token(2,1))
    {
      $event->answerMessage("Usage: /currency [from] [to]\nExample: /currency EUR USD");
      return false;
    }

    $from = strtoupper( $text->token(1,1) );
    $to   = strtoupper( $text->token(2,1) );
    $result = $this->getExchangeRate($from,$to);
    $event->answerMessage( $result ? "1 $from = $result $to" : "Can't find exchange rate from $from to $to");
    return false;
  }

  /**
   * Get exchange rate
   *
   * @param string $from currency code
   * @param string $to currency code
   *
   * @return false|number
   */
  private function getExchangeRate($from,$to )
  {
    $context = stream_context_create([
      'http'=> [
        'method'=>"GET",
        'header'=>"Accept: application/vnd.github.v3+json\r\n" .
                  "User-Agent: Mozilla/5.0 (compatible; Joker/1.0; +https://github.com/miamibc/joker-telegram-bot)\r\n"
      ]
    ]);
    // build url, like https://api.coinbase.com/v2/exchange-rates?currency=USD
    $url = self::RATE_URL . '?' . http_build_query(['currency'=>$from]);

    // return false, if no data comes in
    if (!$content = @file_get_contents( $url , false, $context)) return false;

    // return false, if not possible to encode json
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

    // try to pick up rate for currency $to
    return isset($result['data']['rates'][$to]) ? $result['data']['rates'][$to] : false;
  }

}