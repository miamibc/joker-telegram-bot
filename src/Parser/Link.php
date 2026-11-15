<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents an HTTP link.
 * @see https://core.telegram.org/bots/api#link
 *
 * @method string url() URL of the link
 */
class Link extends Base
{

  protected $wrapper = [
  ];

}
