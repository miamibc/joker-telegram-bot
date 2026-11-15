<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about a user boosting a chat.
 * @see https://core.telegram.org/bots/api#chatboostadded
 *
 * @method integer boost_count() Number of boosts added by the user
 */
class ChatBoostAdded extends Base
{

  protected $wrapper = [
  ];

}
