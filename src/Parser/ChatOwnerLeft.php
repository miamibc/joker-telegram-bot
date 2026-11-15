<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about the chat owner leaving the chat.
 * @see https://core.telegram.org/bots/api#chatownerleft
 *
 * @method User new_owner() Optional. The user who will become the new owner of the chat if the previous owner does not return to the chat
 */
class ChatOwnerLeft extends Base
{

  protected $wrapper = [
    'new_owner' => User::class,
  ];

}
