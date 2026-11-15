<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes an amount of Telegram Stars.
 * @see https://core.telegram.org/bots/api#staramount
 *
 * @method integer amount() Integer amount of Telegram Stars, rounded to 0; can be negative
 * @method integer nanostar_amount() Optional. The number of 1/1000000000 shares of Telegram Stars; from -999999999 to 999999999; can be negative if and only if amount is non-positive
 */
class StarAmount extends Base
{

  protected $wrapper = [
  ];

}
