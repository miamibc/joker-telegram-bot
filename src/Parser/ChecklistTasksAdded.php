<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about tasks added to a checklist.
 * @see https://core.telegram.org/bots/api#checklisttasksadded
 *
 * @method Message checklist_message() Optional. Message containing the checklist to which the tasks were added. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method ChecklistTask[] tasks() List of tasks added to the checklist
 */
class ChecklistTasksAdded extends Base
{

  protected $wrapper = [
    'checklist_message' => Message::class,
    'tasks' => ChecklistTask::class,
  ];

}
