<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an incoming query from a forwarded message with a shared widget created by the bot. Use answerWidgetQuery to answer the query. Each user can send at most 5 queries per 5 seconds to the bot from all its widgets.
 * @see https://core.telegram.org/bots/api#sharedwidgetquery
 *
 * @method string id() Unique identifier for this query
 * @method User from() Sender
 * @method WidgetQueryType type() Type of the query
 * @method string data() Data of the query, 1-64 bytes
 * @method string widget_id() Unique identifier of the shared widget, which sent the query
 */
class SharedWidgetQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'type' => WidgetQueryType::class,
  ];

}
