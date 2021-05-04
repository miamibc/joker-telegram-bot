<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 *
 */

namespace Joker\Parser;

use Joker\Database\Sqlite;

/**
 * @method int id()
 * @method string type()
 * @method string title()
 * @method string username()
 * @method ChatPhoto photo()
 * @method string bio()
 * @method string description()
 * @method string invite_link()
 * @method Message pinned_message()
 */
class Chat extends Base
{

  use Sqlite;

  protected $wrapper = [
    'photo' => ChatPhoto::class,
    'pinned_message' => Message::class,
  ];

  public function __toString()
  {
    return $this->name();
  }

  public function name()
  {
    if (isset($this->data['title']))
      return $this->data['title'];
    if (isset($this->data['first_name'], $this->data['last_name']))
      return implode(" ", [$this->data['first_name'], $this->data['last_name']]);
    if (isset($this->data['first_name']))
      return $this->data['first_name'];
    if (isset($this->data['username']))
      return $this->data['username'];
    return "Unknown";
  }

}