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
    if (!isset($this->data['from'])) return false;
    return new User( $this->data['from'] );
  }

  public function getSenderChat()
  {
    if (!isset($this->data['sender_chat'])) return false;
    return new Chat( $this->data['sender_chat'] );
  }

  public function getForwardFrom()
  {
    if (!isset($this->data['forward_from'])) return false;
    return new User( $this->data['forward_from'] );
  }

  public function getForwardFromChat()
  {
    if (!isset($this->data['forward_from_chat'])) return false;
    return new Chat( $this->data['forward_from_chat'] );
  }

  public function getReplyToMessage()
  {
    if (!isset($this->data['reply_to_message'])) return false;
    return new Message( $this->data['reply_to_message']);
  }

  public function getViaBot()
  {
    if (!isset($this->data['via_bot'])) return false;
    return new User( $this->data['via_bot']);
  }

  public function getDate()
  {
    return $this->data['date'];
  }

  public function getEditDate()
  {
    return $this->data['edit_date'];
  }

  public function getChat()
  {
    if (!isset($this->data['chat'])) return false;
    return new Chat( $this->data['chat']);
  }

  public function getText()
  {
    if (!isset($this->data['text'])) return false;
    return new Text($this->data['text']);
  }

  public function getAnimation()
  {
    if (!isset($this->data['animation'])) return false;
    return new Animation( $this->data['animation']);
  }

  public function getAudio()
  {
    if (!isset($this->data['audio'])) return false;
    return new Audio( $this->data['audio']);
  }

  public function getDocument()
  {
    if (!isset($this->data['document'])) return false;
    return new Document( $this->data['document']);
  }

  public function getEntities()
  {
    if (!isset($this->data['entities'])) return false;
    $result = [];
    foreach ($this->data['entities'] as $entity)
    {
      $result[] = new Entity($entity);
    }
    return $result;
  }

}