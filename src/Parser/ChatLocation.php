<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents a location to which a chat is connected.
 * @see https://core.telegram.org/bots/api#chatlocation
 *
 * @method Location location() The location to which the supergroup is connected. Can't be a live location.
 * @method string address() Location address; 1-64 characters, as defined by the chat owner
 */
class ChatLocation extends Base
{

  protected $wrapper = [
    'location' => Location::class,
  ];

}
