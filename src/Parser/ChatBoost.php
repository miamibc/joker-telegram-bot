<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about a chat boost.
 * @see https://core.telegram.org/bots/api#chatboost
 *
 * @method string boost_id() Unique identifier of the boost
 * @method integer add_date() Point in time (Unix timestamp) when the chat was boosted
 * @method integer expiration_date() Point in time (Unix timestamp) when the boost will automatically expire, unless the booster's Telegram Premium subscription is prolonged
 * @method ChatBoostSource source() Source of the added boost
 */
class ChatBoost extends Base
{

  protected $wrapper = [
    'source' => ChatBoostSource::class,
  ];

}
