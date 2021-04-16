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

  public function __toString()
  {
    return $this->getName();
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

  public function getName()
  {
    if (isset($this->data['title']))
      return $this->getTitle();
    if (isset($this->data['first_name'], $this->data['last_name']))
      return implode(" ", [$this->data['first_name'], $this->data['last_name']]);
    if (isset($this->data['first_name']))
      return $this->data['first_name'];
    if (isset($this->data['username']))
      return $this->data['username'];
    return "Unknown";
  }

  public function getPhoto()
  {
    return new ChatPhoto( $this->data['photo'] );
  }

  public function getBio()
  {
    return $this->data['bio'];
  }

  public function getDescription()
  {
    return $this->data['description'];
  }

  public function getInviteLink()
  {
    return $this->data['invite_link'];
  }

  public function getPinnedMessage()
  {
    if (!isset($this->data['pinned_message'])) return false;
    return new Message( $this->data['pinned_message'] );
  }



}