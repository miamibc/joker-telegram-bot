<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a checklist.
 * @see https://core.telegram.org/bots/api#checklist
 *
 * @method string title() Title of the checklist
 * @method MessageEntity[] title_entities() Optional. Special entities that appear in the checklist title
 * @method ChecklistTask[] tasks() List of tasks in the checklist
 * @method bool others_can_add_tasks() Optional. True, if users other than the creator of the list can add tasks to the list
 * @method bool others_can_mark_tasks_as_done() Optional. True, if users other than the creator of the list can mark tasks as done or not done
 */
class Checklist extends Base
{

  protected $wrapper = [
    'title_entities' => MessageEntity::class,
    'tasks' => ChecklistTask::class,
  ];

}
