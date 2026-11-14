<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a bot command.
 * @see https://core.telegram.org/bots/api#botcommand
 *
 * @method string command() Text of the command; 1-32 characters. Can contain only lowercase English letters, digits and underscores.
 * @method string description() Description of the command; 1-256 characters
 * @method bool is_ephemeral() Optional. True, if the command sends an ephemeral message, which can be seen only by the sender of the message and the bot
 */
class BotCommand extends Base
{

  protected $wrapper = [
  ];

}
