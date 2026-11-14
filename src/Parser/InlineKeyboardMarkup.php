<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an inline keyboard that appears right next to the message it belongs to.
 * @see https://core.telegram.org/bots/api#inlinekeyboardmarkup
 *
 * @method array inline_keyboard() Array of button rows, each represented by an Array of InlineKeyboardButton objects
 * @method bool force_reply() Optional. Pass True if the reply interface must be shown to the user, as if they had manually selected the bot's message and tapped 'Reply'. The value of the field can't be changed when the inline keyboard is edited.
 */
class InlineKeyboardMarkup extends Base
{

  protected $wrapper = [
  ];

}
