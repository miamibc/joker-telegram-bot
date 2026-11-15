<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about checklist tasks marked as done or not done.
 * @see https://core.telegram.org/bots/api#checklisttasksdone
 *
 * @method Message checklist_message() Optional. Message containing the checklist whose tasks were marked as done or not done. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method integer[] marked_as_done_task_ids() Optional. Identifiers of the tasks that were marked as done
 * @method integer[] marked_as_not_done_task_ids() Optional. Identifiers of the tasks that were marked as not done
 */
class ChecklistTasksDone extends Base
{

  protected $wrapper = [
    'checklist_message' => Message::class,
  ];

}
