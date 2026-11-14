<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the birthdate of a user.
 * @see https://core.telegram.org/bots/api#birthdate
 *
 * @method integer day() Day of the user's birth; 1-31
 * @method integer month() Month of the user's birth; 1-12
 * @method integer year() Optional. Year of the user's birth
 */
class Birthdate extends Base
{

  protected $wrapper = [
  ];

}
