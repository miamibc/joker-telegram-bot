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
 * @method string persistent_id() Unique identifier of the option, persistent on option addition and deletion
 * @method string text() Option text, 1-100 characters
 * @method MessageEntity[] text_entities() Optional. Special entities that appear in the option text. Currently, only custom emoji entities are allowed in poll option texts
 * @method PollMedia media() Optional. Media added to the poll option
 * @method integer voter_count() Number of users who voted for this option; may be 0 if unknown
 * @method User added_by_user() Optional. User who added the option; omitted if the option wasn't added by a user after poll creation
 * @method Chat added_by_chat() Optional. Chat that added the option; omitted if the option wasn't added by a chat after poll creation
 * @method integer addition_date() Optional. Point in time (Unix timestamp) when the option was added; omitted if the option existed in the original poll
 */
class PollOption extends Base
{

  protected $wrapper = [
    'text_entities' => MessageEntity::class,
    'media' => PollMedia::class,
    'added_by_user' => User::class,
    'added_by_chat' => Chat::class,
  ];


}