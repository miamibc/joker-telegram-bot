<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a topic of a direct messages chat.
 * @see https://core.telegram.org/bots/api#directmessagestopic
 *
 * @method integer topic_id() Unique identifier of the topic. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method User user() Optional. Information about the user that created the topic. Currently, it is always present.
 */
class DirectMessagesTopic extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];

}
