<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an incoming callback query from a callback button in an inline keyboard. If the button that originated the query was attached to a message sent by the bot, the field message will be present. If the button was attached to a message sent via the bot (in inline mode), the field inline_message_id will be present. Exactly one of the fields data or game_short_name will be present.
 * @see https://core.telegram.org/bots/api#callbackquery
 *
 * @method string id Unique identifier for this query
 * @method User   from Sender
 * @method string query Text of the query (up to 256 characters)
 * @method string offset Offset of the results to be returned, can be controlled by the bot
 * @method string chat_type Optional. Type of the chat, from which the inline query was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat
 * @method Location location Optional. Sender location, only for bots that request user location
 *
 */
class InlineQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'location' => Location::class,
  ];

}