<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Entity
{

  private $data = [];

  public function __construct($data)
  {
    $this->data = $data;
  }

  public function getType()
  {
    if (!isset($this->data['type'])) return false;
    return $this->data['type'];
  }

  public function getOffset()
  {
    if (!isset($this->data['offset'])) return false;
    return $this->data['offset'];
  }

  public function getLength()
  {
    if (!isset($this->data['length'])) return false;
    return $this->data['length'];
  }

  public function getUrl()
  {
    if (!isset($this->data['url'])) return false;
    return $this->data['url'];
  }

  public function getUser()
  {
    if (!isset($this->data['user'])) return false;
    return new User( $this->data['user'] );
  }

  public function getLanguage()
  {
    if (!isset($this->data['language'])) return false;
    return $this->data['language'];
  }

}