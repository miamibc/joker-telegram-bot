<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a change in the price of paid messages within a chat.
 * @see https://core.telegram.org/bots/api#paidmessagepricechanged
 *
 * @method integer paid_message_star_count() The new number of Telegram Stars that must be paid by non-administrator users of the supergroup chat for each sent message
 */
class PaidMessagePriceChanged extends Base
{

  protected $wrapper = [
  ];

}
