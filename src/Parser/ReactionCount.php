<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents a reaction added to a message along with the number of times it was added.
 * @see https://core.telegram.org/bots/api#reactioncount
 *
 * @method ReactionType type() Type of the reaction
 * @method integer total_count() Number of times the reaction was added
 */
class ReactionCount extends Base
{

  protected $wrapper = [
    'type' => ReactionType::class,
  ];

}
