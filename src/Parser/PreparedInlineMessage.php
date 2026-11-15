<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes an inline message to be sent by a user of a Mini App.
 * @see https://core.telegram.org/bots/api#preparedinlinemessage
 *
 * @method string id() Unique identifier of the prepared message
 * @method integer expiration_date() Expiration date of the prepared message, in Unix time. Expired prepared messages can no longer be used.
 */
class PreparedInlineMessage extends Base
{

  protected $wrapper = [
  ];

}
