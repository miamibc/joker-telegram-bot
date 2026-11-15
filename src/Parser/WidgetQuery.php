<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an incoming query from a widget in a message sent by the bot or via the bot. Use answerWidgetQuery to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 * @see https://core.telegram.org/bots/api#widgetquery
 *
 * @method string id() Unique identifier for this query
 * @method User from() Sender
 * @method Chat chat() Optional. Chat with the message containing the widget; omitted if the widget is in an inline message
 * @method integer message_id() Optional. Identifier of the message containing the widget; omitted if the widget is in an inline message
 * @method string inline_message_id() Optional. Identifier of the inline message containing the widget
 * @method string chat_instance() Global identifier, uniquely corresponding to the chat to which the message with the widget was sent
 * @method string chat_type() Optional. For widgets in inline messages, type of the chat to which the message was sent. Can be either “sender” for a private chat with the inline query sender, “private”, “group”, “supergroup”, or “channel”.
 * @method WidgetQueryType type() Type of the query
 * @method string data() Data of the query, 1-64 bytes
 * @method string widget_id() Optional. Unique identifier of the shared widget, which sent the query; omitted if the widget isn't shared
 */
class WidgetQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'chat' => Chat::class,
    'type' => WidgetQueryType::class,
  ];

}
