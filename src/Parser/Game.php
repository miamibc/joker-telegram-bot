<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a game. Use BotFather to create and edit games, their short names will act as unique identifiers.
 * @see https://core.telegram.org/bots/api#game
 *
 * @method string title Title of the game
 * @method string description Description of the game
 * @method PhotoSize[] photo 	Array of PhotoSize 	Photo that will be displayed in the game message in chats.
 * @method string text Optional. Brief description of the game or high scores included in the game message. Can be automatically edited to include current high scores for the game when the bot calls setGameScore, or manually edited using editMessageText. 0-4096 characters.
 * @method string text_entities Array of MessageEntity 	Optional. Special entities that appear in text, such as usernames, URLs, bot commands, etc.
 * @method Animation animation Optional. Animation that will be displayed in the game message in chats. Upload via BotFather
 */
class Game extends Base
{

  protected $wrapper = [
    'photo' => PhotoSize::class,
    'animation' => Animation::class,
  ];


}