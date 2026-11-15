<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the connection of the bot with a business account.
 * @see https://core.telegram.org/bots/api#businessconnection
 *
 * @method string id() Unique identifier of the business connection
 * @method User user() Business account user that created the business connection
 * @method integer user_chat_id() Identifier of a private chat with the user who created the business connection. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method integer date() Date the connection was established in Unix time
 * @method BusinessBotRights rights() Optional. Rights of the business bot
 * @method bool is_enabled() True, if the connection is active
 */
class BusinessConnection extends Base
{

  protected $wrapper = [
    'user' => User::class,
    'rights' => BusinessBotRights::class,
  ];

}
