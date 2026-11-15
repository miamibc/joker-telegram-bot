<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a task in a checklist.
 * @see https://core.telegram.org/bots/api#checklisttask
 *
 * @method integer id() Unique identifier of the task
 * @method string text() Text of the task
 * @method MessageEntity[] text_entities() Optional. Special entities that appear in the task text
 * @method User completed_by_user() Optional. User that completed the task; omitted if the task wasn't completed by a user
 * @method Chat completed_by_chat() Optional. Chat that completed the task; omitted if the task wasn't completed by a chat
 * @method integer completion_date() Optional. Point in time (Unix timestamp) when the task was completed; 0 if the task wasn't completed
 */
class ChecklistTask extends Base
{

  protected $wrapper = [
    'text_entities' => MessageEntity::class,
    'completed_by_user' => User::class,
    'completed_by_chat' => Chat::class,
  ];

}
