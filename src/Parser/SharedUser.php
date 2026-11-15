<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about a user that was shared with the bot using a KeyboardButtonRequestUsers button.
 * @see https://core.telegram.org/bots/api#shareduser
 *
 * @method integer user_id() Identifier of the shared user. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so 64-bit integers or double-precision float types are safe for storing these identifiers. The bot may not have access to the user and could be unable to use this identifier, unless the user is already known to the bot by some other means.
 * @method string first_name() Optional. First name of the user, if the name was requested by the bot
 * @method string last_name() Optional. Last name of the user, if the name was requested by the bot
 * @method string username() Optional. Username of the user, if the username was requested by the bot
 * @method PhotoSize[] photo() Optional. Available sizes of the chat photo, if the photo was requested by the bot
 */
class SharedUser extends Base
{

  protected $wrapper = [
    'photo' => PhotoSize::class,
  ];

}
