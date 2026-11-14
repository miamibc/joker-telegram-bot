<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

use Joker\Database\Sqlite;

/**
 * This object represents a Telegram user or bot.
 * @see https://core.telegram.org/bots/api#user
 *
 * @method integer id() Unique identifier for this user or bot. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method bool is_bot() True, if this user is a bot
 * @method string first_name() User's or bot's first name
 * @method string last_name() Optional. User's or bot's last name
 * @method string username() Optional. User's or bot's username
 * @method string language_code() Optional. IETF language tag of the user's language
 * @method bool is_premium() Optional. True, if this user is a Telegram Premium user
 * @method bool added_to_attachment_menu() Optional. True, if this user added the bot to the attachment menu
 * @method bool can_join_groups() Optional. True, if the bot can be invited to groups. Returned only in getMe.
 * @method bool can_read_all_group_messages() Optional. True, if privacy mode is disabled for the bot. Returned only in getMe.
 * @method bool supports_guest_queries() Optional. True, if the bot supports guest queries from chats it is not a member of. Returned only in getMe.
 * @method bool supports_inline_queries() Optional. True, if the bot supports inline queries. Returned only in getMe.
 * @method bool can_connect_to_business() Optional. True, if the bot can be connected to a user account to manage it. Returned only in getMe.
 * @method bool has_main_web_app() Optional. True, if the bot has a main Web App. Returned only in getMe.
 * @method bool has_topics_enabled() Optional. True, if the bot has forum topic mode enabled in private chats. Returned only in getMe.
 * @method bool allows_users_to_create_topics() Optional. True, if the bot allows users to create and delete topics in private chats. Returned only in getMe.
 * @method bool can_manage_bots() Optional. True, if other bots can be created to be controlled by the bot. Returned only in getMe.
 * @method bool supports_join_request_queries() Optional. True, if the bot supports join request queries and can be assigned to process them. Returned only in getMe.
 */
class User extends Base
{

  use Sqlite;

  protected $wrapper = [
  ];

  public function __toString()
  {
    return $this->name().'';
  }

  public function name()
  {
    if (isset($this->data['first_name'], $this->data['last_name']))
      return trim(implode(' ', [$this->data['first_name'], $this->data['last_name']]));
    if (isset($this->data['first_name']))
      return $this->data['first_name'];
    if (isset($this->data['username']))
      return $this->data['username'];
    return "Unknown";
  }

}