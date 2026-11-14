<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about an option deleted from a poll.
 * @see https://core.telegram.org/bots/api#polloptiondeleted
 *
 * @method Message poll_message() Optional. Message containing the poll from which the option was deleted, if known. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method string option_persistent_id() Unique identifier of the deleted option
 * @method string option_text() Option text
 * @method MessageEntity[] option_text_entities() Optional. Special entities that appear in the option_text
 */
class PollOptionDeleted extends Base
{

  protected $wrapper = [
    'poll_message' => Message::class,
    'option_text_entities' => MessageEntity::class,
  ];

}
