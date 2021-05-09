<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * @method int id()
 * @method int update_id()
 * @method Message message()
 * @method Message edited_message()
 * @method Message channel_post()
 * @method Message edited_channel_post()
 * @method Message poll()
 * @method Message poll_answer()
 */
class Update extends Base
{

  protected $wrapper = [
    'message' => Message::class,
    'edited_message' => Message::class,
    'channel_post' => Message::class,
    'edited_channel_post' => Message::class,
    'poll' => Message::class,
    'poll_answer' => Message::class,
  ];

}