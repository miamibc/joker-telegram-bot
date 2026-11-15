<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about an ownership change in the chat.
 * @see https://core.telegram.org/bots/api#chatownerchanged
 *
 * @method User new_owner() The new owner of the chat
 */
class ChatOwnerChanged extends Base
{

  protected $wrapper = [
    'new_owner' => User::class,
  ];

}
