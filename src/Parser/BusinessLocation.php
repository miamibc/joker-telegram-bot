<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Contains information about the location of a Telegram Business account.
 * @see https://core.telegram.org/bots/api#businesslocation
 *
 * @method string address() Address of the business
 * @method Location location() Optional. Location of the business
 */
class BusinessLocation extends Base
{

  protected $wrapper = [
    'location' => Location::class,
  ];

}
