<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a message about the completion of a giveaway with public winners.
 * @see https://core.telegram.org/bots/api#giveawaywinners
 *
 * @method Chat chat() The chat that created the giveaway
 * @method integer giveaway_message_id() Identifier of the message with the giveaway in the chat
 * @method integer winners_selection_date() Point in time (Unix timestamp) when winners of the giveaway were selected
 * @method integer winner_count() Total number of winners in the giveaway
 * @method User[] winners() List of up to 100 winners of the giveaway
 * @method integer additional_chat_count() Optional. The number of other chats the user had to join in order to be eligible for the giveaway
 * @method integer prize_star_count() Optional. The number of Telegram Stars that were split between giveaway winners; for Telegram Star giveaways only
 * @method integer premium_subscription_month_count() Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
 * @method integer unclaimed_prize_count() Optional. Number of undistributed prizes
 * @method bool only_new_members() Optional. True, if only users who had joined the chats after the giveaway started were eligible to win
 * @method bool was_refunded() Optional. True, if the giveaway was canceled because the payment for it was refunded
 * @method string prize_description() Optional. Description of additional giveaway prize
 */
class GiveawayWinners extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
    'winners' => User::class,
  ];

}
