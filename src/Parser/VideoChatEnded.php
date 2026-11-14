<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about a video chat ended in the chat.
 * @see https://core.telegram.org/bots/api#videochatended
 *
 * @method integer duration() Video chat duration in seconds
 */
class VideoChatEnded extends Base
{

  protected $wrapper = [
  ];

}
