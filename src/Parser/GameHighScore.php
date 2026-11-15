<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents one row of the high scores table for a game.
 * @see https://core.telegram.org/bots/api#gamehighscore
 *
 * @method integer position() Position in high score table for the game
 * @method User user() User
 * @method integer score() Score
 */
class GameHighScore extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];

}
