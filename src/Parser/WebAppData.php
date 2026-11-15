<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes data sent from a Web App to the bot.
 * @see https://core.telegram.org/bots/api#webappdata
 *
 * @method string data() The data. Be aware that a bad client can send arbitrary data in this field.
 * @method string button_text() Text of the web_app keyboard button from which the Web App was opened. Be aware that a bad client can send arbitrary data in this field.
 */
class WebAppData extends Base
{

  protected $wrapper = [
  ];

}
