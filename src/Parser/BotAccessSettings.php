<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the access settings of a bot.
 * @see https://core.telegram.org/bots/api#botaccesssettings
 *
 * @method bool is_access_restricted() True, if only selected users can access the bot. The bot's owner can always access it.
 * @method User[] added_users() Optional. The list of other users who have access to the bot if the access is restricted
 */
class BotAccessSettings extends Base
{

  protected $wrapper = [
    'added_users' => User::class,
  ];

}
