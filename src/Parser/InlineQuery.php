<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an incoming inline query. When the user sends an empty query, your bot could return some default or trending results.
 * @see https://core.telegram.org/bots/api#inlinequery
 *
 * @method string id() Unique identifier for this query
 * @method User from() Sender
 * @method string query() Text of the query (up to 256 characters)
 * @method string offset() Offset of the results to be returned, can be controlled by the bot
 * @method string chat_type() Optional. Type of the chat from which the inline query was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”. The chat type should be always known for requests sent from official clients and most third-party clients, unless the request was sent from a secret chat.
 * @method Location location() Optional. Sender location, only for bots that request user location
 */
class InlineQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'location' => Location::class,
  ];

  public function answer( $data )
  {
    if (!$update = $this->parent()) return false;
    if (!$bot = $update->parent()) return false;

    $data = array_merge( $data, [
      'inline_query_id' => $this->id(),
    ]);

    return $bot->customRequest('answerInlineQuery', $data);
  }

}