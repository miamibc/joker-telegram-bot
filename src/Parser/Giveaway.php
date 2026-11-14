<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a message about a scheduled giveaway.
 * @see https://core.telegram.org/bots/api#giveaway
 *
 * @method Chat[] chats() The list of chats which the user must join to participate in the giveaway
 * @method integer winners_selection_date() Point in time (Unix timestamp) when winners of the giveaway will be selected
 * @method integer winner_count() The number of users which are supposed to be selected as winners of the giveaway
 * @method bool only_new_members() Optional. True, if only users who join the chats after the giveaway started should be eligible to win
 * @method bool has_public_winners() Optional. True, if the list of giveaway winners will be visible to everyone
 * @method string prize_description() Optional. Description of additional giveaway prize
 * @method string[] country_codes() Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which eligible users for the giveaway must come. If empty, then all users can participate in the giveaway. Users with a phone number that was bought on Fragment can always participate in giveaways.
 * @method integer prize_star_count() Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 * @method integer premium_subscription_month_count() Optional. The number of months the Telegram Premium subscription won from the giveaway will be active for; for Telegram Premium giveaways only
 */
class Giveaway extends Base
{

  protected $wrapper = [
    'chats' => Chat::class,
  ];

}
