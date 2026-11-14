<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a list of boosts added to a chat by a user.
 * @see https://core.telegram.org/bots/api#userchatboosts
 *
 * @method ChatBoost[] boosts() The list of boosts added to the chat by the user
 */
class UserChatBoosts extends Base
{

  protected $wrapper = [
    'boosts' => ChatBoost::class,
  ];

}
