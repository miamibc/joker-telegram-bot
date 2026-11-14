<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the rating of a user based on their Telegram Star spendings.
 * @see https://core.telegram.org/bots/api#userrating
 *
 * @method integer level() Current level of the user, indicating their reliability when purchasing digital goods and services. A higher level suggests a more trustworthy customer; a negative level is likely reason for concern.
 * @method integer rating() Numerical value of the user's rating; the higher the rating, the better
 * @method integer current_level_rating() The rating value required to get the current level
 * @method integer next_level_rating() Optional. The rating value required to get to the next level; omitted if the maximum level was reached
 */
class UserRating extends Base
{

  protected $wrapper = [
  ];

}
