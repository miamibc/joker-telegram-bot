<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * @method string type()
 * @method string offset()
 * @method string length()
 * @method string url()
 * @method User   user()
 * @method string language()
 */
class Entity extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];

}