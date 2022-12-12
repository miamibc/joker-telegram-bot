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
 * @method string is_bot() True, if this user is a bot
 * @method string first_name() User's or bot's first name
 * @method string last_name() Optional. User's or bot's last name
 * @method string username() Optional. User's or bot's username
 * @method string language_code() Optional. IETF language tag of the user's language
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