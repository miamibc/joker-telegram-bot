<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about a change in auto-delete timer settings.
 * @see https://core.telegram.org/bots/api#messageautodeletetimerchanged
 *
 * @method integer message_auto_delete_time() New auto-delete time for messages in the chat; in seconds
 */
class MessageAutoDeleteTimerChanged extends Base
{

  protected $wrapper = [
  ];

}
