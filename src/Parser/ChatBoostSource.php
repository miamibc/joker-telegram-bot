<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the source of a chat boost. It can be one of
 * Currently can be one of ChatBoostSourcePremium, ChatBoostSourceGiftCode, ChatBoostSourceGiveaway — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#chatboostsource
 *
 * @method string source() Source of the boost, always “premium”
 * @method User user() User that boosted the chat
 * @method integer giveaway_message_id() ChatBoostSourceGiveaway only. Identifier of a message in the chat with the giveaway; the message could have been deleted already. May be 0 if the message isn't sent yet.
 * @method integer prize_star_count() ChatBoostSourceGiveaway only. Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 * @method bool is_unclaimed() ChatBoostSourceGiveaway only. Optional. True, if the giveaway was completed, but there was no user to win the prize
 */
class ChatBoostSource extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];

}
