<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Message
{

  private $data = [];

  public function __construct( $data )
  {
    $this->data = $data;
  }

  public function getId()
  {
    return $this->getMessageId();
  }

  public function getMessageId()
  {
    return $this->data['message_id'];
  }

  public function getFrom()
  {
    return new User( $this->data['from'] );
  }

  public function getReplyToMessage()
  {
    return new Message( $this->data['reply_to_message']);
  }

  public function getDate()
  {
    return $this->data['date'];
  }

  public function getChat()
  {
    return new Chat( $this->data['chat']);
  }

  public function getText()
  {
    return new Text($this->data['text']);
  }
}