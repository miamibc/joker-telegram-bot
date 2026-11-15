<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a chat background.
 * @see https://core.telegram.org/bots/api#chatbackground
 *
 * @method BackgroundType type() Type of the background
 */
class ChatBackground extends Base
{

  protected $wrapper = [
    'type' => BackgroundType::class,
  ];

}
