<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represent a user's profile pictures.
 * @see https://core.telegram.org/bots/api#userprofilephotos
 *
 * @method integer total_count() Total number of profile pictures the target user has
 * @method array photos() Requested profile pictures (in up to 4 sizes each)
 */
class UserProfilePhotos extends Base
{

  protected $wrapper = [
  ];

}
