<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about a chat that was shared with the bot using a KeyboardButtonRequestChat button.
 * @see https://core.telegram.org/bots/api#chatshared
 *
 * @method integer request_id() Identifier of the request
 * @method integer chat_id() Identifier of the shared chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot may not have access to the chat and could be unable to use this identifier, unless the chat is already known to the bot by some other means.
 * @method string title() Optional. Title of the chat, if the title was requested by the bot
 * @method string username() Optional. Username of the chat, if the username was requested by the bot and available
 * @method PhotoSize[] photo() Optional. Available sizes of the chat photo, if the photo was requested by the bot
 */
class ChatShared extends Base
{

  protected $wrapper = [
    'photo' => PhotoSize::class,
  ];

}
