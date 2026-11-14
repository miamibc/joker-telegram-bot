<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes an inline message sent by a guest bot.
 * @see https://core.telegram.org/bots/api#sentguestmessage
 *
 * @method string inline_message_id() Identifier of the sent inline message
 */
class SentGuestMessage extends Base
{

  protected $wrapper = [
  ];

}
