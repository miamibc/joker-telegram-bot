<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

use Joker\Database\Sqlite;

/**
 * @method integer id()
 * @method string username()
 * @method string is_bot()
 * @method string language_code()
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
      return implode(" ", [$this->data['first_name'], $this->data['last_name']]);
    if (isset($this->data['first_name']))
      return $this->data['first_name'];
    if (isset($this->data['username']))
      return $this->data['username'];
    return "Unknown";
  }

}