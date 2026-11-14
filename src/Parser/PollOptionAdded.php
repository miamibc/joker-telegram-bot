<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about an option added to a poll.
 * @see https://core.telegram.org/bots/api#polloptionadded
 *
 * @method Message poll_message() Optional. Message containing the poll to which the option was added, if known. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method string option_persistent_id() Unique identifier of the added option
 * @method string option_text() Option text
 * @method MessageEntity[] option_text_entities() Optional. Special entities that appear in the option_text
 */
class PollOptionAdded extends Base
{

  protected $wrapper = [
    'poll_message' => Message::class,
    'option_text_entities' => MessageEntity::class,
  ];

}
