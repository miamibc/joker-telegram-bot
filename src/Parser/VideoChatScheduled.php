<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about a video chat scheduled in the chat.
 * @see https://core.telegram.org/bots/api#videochatscheduled
 *
 * @method integer start_date() Point in time (Unix timestamp) when the video chat is supposed to be started by a chat administrator
 */
class VideoChatScheduled extends Base
{

  protected $wrapper = [
  ];

}
