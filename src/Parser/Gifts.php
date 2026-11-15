<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represent a list of gifts.
 * @see https://core.telegram.org/bots/api#gifts
 *
 * @method Gift[] gifts() The list of gifts
 */
class Gifts extends Base
{

  protected $wrapper = [
    'gifts' => Gift::class,
  ];

}
