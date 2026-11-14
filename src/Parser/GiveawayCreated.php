<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about the creation of a scheduled giveaway.
 * @see https://core.telegram.org/bots/api#giveawaycreated
 *
 * @method integer prize_star_count() Optional. The number of Telegram Stars to be split between giveaway winners; for Telegram Star giveaways only
 */
class GiveawayCreated extends Base
{

  protected $wrapper = [
  ];

}
