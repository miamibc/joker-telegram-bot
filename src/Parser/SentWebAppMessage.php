<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes an inline message sent by a Web App on behalf of a user.
 * @see https://core.telegram.org/bots/api#sentwebappmessage
 *
 * @method string inline_message_id() Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message.
 */
class SentWebAppMessage extends Base
{

  protected $wrapper = [
  ];

}
