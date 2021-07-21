<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a shipping address
 * @see https://core.telegram.org/bots/api#shippingaddress
 *
 * @method string country_code ISO 3166-1 alpha-2 country code
 * @method string state State, if applicable
 * @method string city City
 * @method string street_line1 First line for the address
 * @method string street_line2 Second line for the address
 * @method string post_code Address post code
 */
class ShippingAddress extends Base
{

  protected $wrapper = [
  ];

}