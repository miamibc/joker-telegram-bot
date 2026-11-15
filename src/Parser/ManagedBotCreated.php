<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about the bot that was created to be managed by the current bot.
 * @see https://core.telegram.org/bots/api#managedbotcreated
 *
 * @method User bot() Information about the bot. The bot's token can be fetched using the method getManagedBotToken.
 */
class ManagedBotCreated extends Base
{

  protected $wrapper = [
    'bot' => User::class,
  ];

}
