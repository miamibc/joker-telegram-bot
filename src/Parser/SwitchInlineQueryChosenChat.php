<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an inline button that switches the current user to inline mode in a chosen chat, with an optional default inline query.
 * @see https://core.telegram.org/bots/api#switchinlinequerychosenchat
 *
 * @method string query() Optional. The default inline query to be inserted in the input field. If left empty, only the bot's username will be inserted.
 * @method bool allow_user_chats() Optional. True, if private chats with users can be chosen
 * @method bool allow_bot_chats() Optional. True, if private chats with bots can be chosen
 * @method bool allow_group_chats() Optional. True, if group and supergroup chats can be chosen
 * @method bool allow_channel_chats() Optional. True, if channel chats can be chosen
 */
class SwitchInlineQueryChosenChat extends Base
{

  protected $wrapper = [
  ];

}
