<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class User
{

  private $data = [];

  public function __construct( $data )
  {
    $this->data = $data;
  }

  public function __toString()
  {
    return $this->getName() . '';
  }

  public function getId()
  {
    return $this->data['id'];
  }

  public function getName()
  {
    if (isset($this->data['first_name'], $this->data['last_name']))
      return implode(" ", [$this->data['first_name'], $this->data['last_name']]);
    if (isset($this->data['first_name']))
      return $this->data['first_name'];
    if (isset($this->data['username']))
      return $this->data['username'];
    return "Unknown";
  }

  public function isBot()
  {
    return $this->data['is_bot'];
  }

  public function getLanguageCode()
  {
    return $this->data['language_code'];
  }

}