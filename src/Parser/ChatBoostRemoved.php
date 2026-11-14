<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a boost removed from a chat.
 * @see https://core.telegram.org/bots/api#chatboostremoved
 *
 * @method Chat chat() Chat which was boosted
 * @method string boost_id() Unique identifier of the boost
 * @method integer remove_date() Point in time (Unix timestamp) when the boost was removed
 * @method ChatBoostSource source() Source of the removed boost
 */
class ChatBoostRemoved extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
    'source' => ChatBoostSource::class,
  ];

}
