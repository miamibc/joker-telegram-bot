<?php
/**
 * Wrapper of TelegrambotApi/Update, allows to add new functions and
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class Event
{

  private $bot;
  public  $data;

  public function __construct( Bot $bot, $data)
  {
    $this->bot  = $bot;
    $this->data = $data;
  }


  public function answerMessage( $text )
  {
    if (isset($this->data['message']['chat']['id']))
      $this->bot->sendMessage( $this->data['message']['chat']['id'], $text );
  }

  public function deleteMessage()
  {
    if (isset($this->data['message']['chat']['id'], $this->data['message']['message_id']))
      $this->bot->deleteMessage( $this->data['message']['chat']['id'], $this->data['message']['message_id'] );
  }

  public function answerSticker( $file_id )
  {
    if (isset($this->data['message']['chat']['id']))
    $this->bot->sendSticker( $this->data['message']['chat']['id'], $file_id );
  }

  /**
   * Get all characteristics of update with true/false values
   * @return array
   */
  public function getParameters()
  {
    return [
      'private' => $private = (isset($this->data['message']['chat']['type'])
                               && $this->data['message']['chat']['type'] == 'private'),
      'public'  => !$private,
      'sticker' => isset($this->data['message']['sticker']),
      'text'    => isset($this->data['message']['text']),
      'message' => isset($this->data['message']),
      'empty'   => empty($this->data),
    ];
  }

  public function getMessageText()
  {
    return $this->data['message']['text'];
  }

  public function getMessageFrom()
  {
    if (isset($this->data['message']['from']['first_name']) && isset($this->data['message']['from']['last_name']))
      return trim( $this->data['message']['from']['first_name'] .' '. $this->data['message']['from']['last_name']);

    if (isset($this->data['message']['from']['username']))
      return trim( $this->data['message']['from']['username']);

    return 'Unknown';
  }

  public function getData()
  {
    return $this->data;
  }

  public function toJson()
  {
    return json_encode( $this->data);
  }
}