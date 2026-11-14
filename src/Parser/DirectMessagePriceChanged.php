<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a change in the price of direct messages sent to a channel chat.
 * @see https://core.telegram.org/bots/api#directmessagepricechanged
 *
 * @method bool are_direct_messages_enabled() True, if direct messages are enabled for the channel chat; False otherwise
 * @method integer direct_message_star_count() Optional. The new number of Telegram Stars that must be paid by users for each direct message sent to the channel. Does not apply to users who have been exempted by administrators. Defaults to 0.
 */
class DirectMessagePriceChanged extends Base
{

  protected $wrapper = [
  ];

}
