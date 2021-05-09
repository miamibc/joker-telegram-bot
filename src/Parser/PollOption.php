<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about one answer option in a poll.
 * @see https://core.telegram.org/bots/api#polloption
 *
 * @method string text Option text, 1-100 characters
 * @method integer voter_count Number of users that voted for this option
 */
class PollOption extends Base
{

  protected $wrapper = [
  ];


}