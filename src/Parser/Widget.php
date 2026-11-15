<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes an HTML widget.
 * @see https://core.telegram.org/bots/api#widget
 *
 * @method string id() Optional. Unique identifier of the shared widget, which can be used to send the widget again using the method sendSharedWidget; omitted if the widget isn't shared
 * @method integer width() Optional. Width of the widget in logical pixels; omitted if the widget must occupy the whole width of the message bubble
 * @method integer height() Optional. Height of the widget in logical pixels; omitted if the height of the widget isn't fixed
 * @method string html() HTML code of the widget
 * @method string public_data() Optional. Public data of the widget, which is available wherever the widget is shown. All messages with a shared widget have the same public data.
 * @method string chat_data() Optional. Data of the widget, which is specific to the message
 */
class Widget extends Base
{

  protected $wrapper = [
  ];

}
