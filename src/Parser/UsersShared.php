<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about the users whose identifiers were shared with the bot using a KeyboardButtonRequestUsers button.
 * @see https://core.telegram.org/bots/api#usersshared
 *
 * @method integer request_id() Identifier of the request
 * @method SharedUser[] users() Information about users shared with the bot
 */
class UsersShared extends Base
{

  protected $wrapper = [
    'users' => SharedUser::class,
  ];

}
