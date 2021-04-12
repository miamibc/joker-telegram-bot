<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Chat
{

  private $data = [];

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function getId()
  {
    return $this->data['id'];
  }

  public function getType()
  {
    return $this->data['type'];
  }

  public function getTitle()
  {
    return $this->data['title'];
  }

  public function getUsername()
  {
    return $this->data['username'];
  }

}