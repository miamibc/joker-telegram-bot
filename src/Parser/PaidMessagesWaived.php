<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about the sender of the message using a link of the other user, which allows sending messages to the user without paying Telegram Stars. Currently holds no information.
 * @see https://core.telegram.org/bots/api#paidmessageswaived
 *
 */
class PaidMessagesWaived extends Base
{

  protected $wrapper = [
  ];

}
