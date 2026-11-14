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
 * @method string id() Unique identifier for this query
 * @method User from() Sender
 * @method Message message() Optional. Message sent by the bot with the callback button that originated the query
 * @method string inline_message_id() Optional. Identifier of the message sent via the bot in inline mode, that originated the query
 * @method string chat_instance() Global identifier, uniquely corresponding to the chat to which the message with the callback button was sent. Useful for high scores in games.
 * @method string data() Optional. Data associated with the callback button. Be aware that the message originated the query can contain no callback buttons with this data.
 * @method string game_short_name() Optional. Short name of a Game to be returned, serves as the unique identifier for the game
 */
class CallbackQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'message' => Message::class,
  ];

  public function answer( $data )
  {

    if (!$update = $this->parent()) return false;
    if (!$bot = $update->parent()) return false;

    $data = array_merge( $data, [
      'callback_query_id' => $this->id(),
    ]);

    return $bot->customRequest('answerCallbackQuery', $data);

  }
}