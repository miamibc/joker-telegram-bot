<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about the creation, token update, or owner update of a bot that is managed by the current bot.
 * @see https://core.telegram.org/bots/api#managedbotupdated
 *
 * @method User user() User that created the bot
 * @method User bot() Information about the bot. Token of the bot can be fetched using the method getManagedBotToken.
 */
class ManagedBotUpdated extends Base
{

  protected $wrapper = [
    'user' => User::class,
    'bot' => User::class,
  ];

}
