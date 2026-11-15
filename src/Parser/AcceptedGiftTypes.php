<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the types of gifts that can be gifted to a user or a chat.
 * @see https://core.telegram.org/bots/api#acceptedgifttypes
 *
 * @method bool unlimited_gifts() True, if unlimited regular gifts are accepted
 * @method bool limited_gifts() True, if limited regular gifts are accepted
 * @method bool unique_gifts() True, if unique gifts or gifts that can be upgraded to unique for free are accepted
 * @method bool premium_subscription() True, if a Telegram Premium subscription is accepted
 * @method bool gifts_from_channels() True, if transfers of unique gifts from channels are accepted
 */
class AcceptedGiftTypes extends Base
{

  protected $wrapper = [
  ];

}
