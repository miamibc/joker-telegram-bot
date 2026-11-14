<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about the completion of a giveaway without public winners.
 * @see https://core.telegram.org/bots/api#giveawaycompleted
 *
 * @method integer winner_count() Number of winners in the giveaway
 * @method integer unclaimed_prize_count() Optional. Number of undistributed prizes
 * @method Message giveaway_message() Optional. Message with the giveaway that was completed, if it wasn't deleted
 * @method bool is_star_giveaway() Optional. True, if the giveaway is a Telegram Star giveaway. Otherwise, currently, the giveaway is a Telegram Premium giveaway.
 */
class GiveawayCompleted extends Base
{

  protected $wrapper = [
    'giveaway_message' => Message::class,
  ];

}
