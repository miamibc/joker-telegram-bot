<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a story.
 * @see https://core.telegram.org/bots/api#story
 *
 * @method Chat chat() Chat that posted the story
 * @method integer id() Unique identifier for the story in the chat
 */
class Story extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
  ];

}
