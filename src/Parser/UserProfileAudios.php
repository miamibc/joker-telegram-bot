<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents the audios displayed on a user's profile.
 * @see https://core.telegram.org/bots/api#userprofileaudios
 *
 * @method integer total_count() Total number of profile audios for the target user
 * @method Audio[] audios() Requested profile audios
 */
class UserProfileAudios extends Base
{

  protected $wrapper = [
    'audios' => Audio::class,
  ];

}
