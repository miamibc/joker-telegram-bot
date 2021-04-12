<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Update
{

  private $data = [];

  public function __construct( $data )
  {
    $this->data = $data;
  }

  public function getId()
  {
    return $this->getUpdateId();
  }

  public function getUpdateId()
  {
    return $this->data['update_id'];
  }

  public function getMessage()
  {
    if (!isset($this->data['message'])) return false;
    return new Message( $this->data['message']);
  }

  public function getEditedMessage()
  {
    if (!isset($this->data['edited_message'])) return false;
    return new Message( $this->data['edited_message']);
  }

  public function getChannelPost()
  {
    if (!isset($this->data['channel_post'])) return false;
    return new Message( $this->data['channel_post']);
  }

  public function getEditedChannelPost()
  {
    if (!isset($this->data['edited_channel_post'])) return false;
    return new Message( $this->data['edited_channel_post']);
  }

  public function getPoll()
  {
    if (!isset($this->data['poll'])) return false;
    return new Message( $this->data['poll']);
  }

  public function getPollAnswer()
  {
    if (!isset($this->data['poll_answer'])) return false;
    return new Message( $this->data['poll_answer']);
  }

}