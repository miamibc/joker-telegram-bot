<?php
/**
 * Joker Event
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

use Joker\Parser\Message;

class Event
{

  private $bot;
  public  $data;

  public function __construct( Bot $bot, $data)
  {
    $this->bot  = $bot;
    $this->data = $data;
  }

  public function sendMessage( $chat_id, $text, $options = [] )
  {
    return $this->bot->sendMessage( $chat_id, $text, $options );
  }

  public function answerMessage( $text, $options = [] )
  {
    if (!isset($this->data['message']['chat']['id'])) return false;
    return $this->bot->sendMessage( $this->data['message']['chat']['id'], $text, $options );
  }

  public function deleteMessage()
  {
    if (!isset($this->data['message']['chat']['id'], $this->data['message']['message_id'])) return false;
    return $this->bot->deleteMessage( $this->data['message']['chat']['id'], $this->data['message']['message_id'] );
  }

  public function answerSticker( $file_id, $options = [] )
  {
    if (!isset($this->data['message']['chat']['id'])) return false;
    return $this->bot->sendSticker( $this->data['message']['chat']['id'], $file_id, $options );
  }

  public function answerPhoto( $file, $options = [])
  {
    if (!isset($this->data['message']['chat']['id'])) return false;
    return $this->bot->sendPhoto( $this->data['message']['chat']['id'], $file, $options );
  }

  public function forwardMessage( $chat_id , $options = [])
  {
    if (!isset($this->data['message']['chat']['id'], $this->data['message']['message_id'])) return false;
    return $this->bot->forwardMessage( $chat_id, $this->data['message']['chat']['id'], $this->data['message']['message_id'], $options );
  }

  public function customRequest( $method, $data = [])
  {
    return $this->bot->customRequest($method, $data);
  }

  /**
   * Get all characteristics of update with true/false values
   * @return array
   */
  public function getTags()
  {
    return [
      'private' => $private = (
        isset($this->data['message']['chat']['type'])
        && in_array( $this->data['message']['chat']['type'], ['private'])
      ),
      'public'  => !$private,
      'group'   => isset($this->data['message']['chat']['type'])
                   && in_array( $this->data['message']['chat']['type'], ['group', 'supergroup', 'channel']),
      'sticker'   => isset($this->data['message']['sticker']),
      'entities'  => isset($this->data['message']['entities']),
      'animation' => isset($this->data['message']['animation']),
      'audio'     => isset($this->data['message']['audio']),
      'document'  => isset($this->data['message']['document']),
      'video'     => isset($this->data['message']['video']),
      'voice'     => isset($this->data['message']['voice']),
      'contact'   => isset($this->data['message']['contact']),
      'dice'      => isset($this->data['message']['dice']),
      'game'      => isset($this->data['message']['game']),
      'photo'     => isset($this->data['message']['photo']),
      'caption'   => isset($this->data['message']['caption']),
      'text'      => isset($this->data['message']['text']),
      'reply'     => isset($this->data['message']['reply_to_message']),
      'forward'   => isset($this->data['message']['forward_from'])
                  || isset($this->data['message']['forward_from_chat'])
                  || isset($this->data['message']['forward_from_message_id'])
                  || isset($this->data['message']['forward_date']),
      'poll'      => isset($this->data['message']['poll']),
      'edit'      => isset($this->data['message']['edit_date']),
      'location'  => isset($this->data['message']['venue'])|| isset($this->data['message']['location'])   ,
      'join'      => isset($this->data['message']['new_chat_members']),
      'leave'     => isset($this->data['message']['left_chat_member']),
      'pin'       => isset($this->data['message']['pinned_message']),
      'message'   => isset($this->data['message']),
      'empty'     => empty($this->data),
    ];
  }

  public function getMessage()
  {
    return new Message( $this->data['message'] );
  }

  public function getMessageText()
  {
    $text = "";
    if ( isset( $this->data['message']['text'] ))
      $text = $this->data['message']['text'];
    elseif ( isset( $this->data['message']['caption'] ))
      $text = $this->data['message']['caption'];
    return trim($text);
  }

  public function getMessageTextParser()
  {
    return new MessageTextParser( $this->getMessageText() );
  }

  public function getMessageId()
  {
    return($this->data['message']['message_id']);
  }

  public function getMessageChatId()
  {
    return isset($this->data['message']['chat']['id']) ? $this->data['message']['chat']['id'] : null;
  }

  public function getMessageFromId()
  {
    return isset($this->data['message']['from']['id']) ? $this->data['message']['from']['id'] : null;
  }

  public function getMessageFrom()
  {
    if (isset($this->data['message']['from']['first_name'], $this->data['message']['from']['last_name']))
      return trim( $this->data['message']['from']['first_name'] .' '. $this->data['message']['from']['last_name']);

    if (isset($this->data['message']['from']['first_name']))
      return trim( $this->data['message']['from']['first_name']);

    if (isset($this->data['message']['from']['username']))
      return trim( $this->data['message']['from']['username']);

    return 'Unknown';
  }

  public function getData()
  {
    return $this->data;
  }

  public function getBot()
  {
    return $this->bot;
  }

  public function toJson()
  {
    return json_encode( $this->data);
  }
}