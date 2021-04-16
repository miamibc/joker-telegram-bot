<?php
/**
 * Forwarder plugin for Joker
 *
 * Forwards messages from one chat to another
 *
 * Array or options consists of items with elements:
 * - from - (number or array of numbers) one or many chat_ids to read messages from
 * - text - (string or array of strings) one or many patterns of text with *wildcards* or ?questions?
 * - to   - (number or array of numbers) one or many chat_ids to send message to
 * - forward - (bool, default is true)  should bot forward message, or just copy
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Event;
use Joker\Plugin;

class Mana extends Plugin
{

  /**
   * Reply to /me command with information (for now only rating available)
   *
   * @param Event $event
   *
   * @return false|void
   */
  public function onPublicText( Event $event )
  {
    $parser = $event->getMessageTextParser();
    if ($parser->trigger() !== 'me') return;

    $rating = $this->getRating( $event->getMessageFromId());
    $event->answerMessage("Mana " . $rating);
    return false;
  }


  public function onPublicTextReply( Event $event )
  {
    $message = $event->getMessage();

    // check all required fields exists in request
    if (!isset($message['from']['id'], $message['reply_to_message']['from']['id'])) return;

    // get information about authors of both messages
    $from_id   = $message['from']['id'];
    $to_id     = $message['reply_to_message']['from']['id'];
    $from_name = $this->getNameOf($message['from']);
    $to_name   = $this->getNameOf($message['reply_to_message']['from']);

    // get ratings
    $rating = [
      'from' => $this->getRating($from_id),
      'to' => $this->getRating($to_id),
    ];

    // check first leter, is it + or -
    switch (substr( $event->getMessageText(), 0, 1))
    {
      case '+':
        $rating['from_new'] = $rating['from']-.5;
        $rating['to_new'] = $rating['to']+1;
        $rating['action'] = "$from_name ({$rating['from']} -.5) shared mana with $to_name ({$rating['to']} +1)";
        break;
      case '-':
        $rating['from_new'] = $rating['from']+.5;
        $rating['to_new'] = $rating['to']-1;
        $rating['action'] = "$from_name ({$rating['from']} +.5) sucked mana from $to_name ({$rating['to']} -1)";
        break;
      default: return;
    }

    // not enough mana
    if ($rating['from_new'] < 0)
    {
      $event->answerMessage("Not enough mana to do that");
      return false;
    }
    // not enough mana
    if ($rating['to_new'] < 0)
    {
      $event->answerMessage("No mana here");
      return false;
    }

    // cannot share mana with yourself
    if (false) //$from_id == $to_id)
    {
      $event->answerMessage('Sorry d0g, you cannot share mana with yourself');
      return false;
    }

    // save ratings
    $this->setRating($from_id, $rating['from_new']);
    $this->setRating($to_id, $rating['to_new']);

    // answer
    $event->answerMessage( $rating['action'] );
    return false;
  }

  private function getRating( $user_id )
  {
    if (!file_exists('data/carma')) mkdir('data/carma');
    return file_exists("data/carma/$user_id") ? file_get_contents("data/carma/$user_id") : 0;
  }

  private function setRating( $user_id, $value )
  {
    if (!file_exists('data/carma')) mkdir('data/carma');
    file_put_contents("data/carma/$user_id", $value);
  }

  /**
   * Extracts name from User object
   * @param $from
   *
   * @return string
   */
  private function getNameOf( $from )
  {

    if (isset($from['first_name'],$from['last_name']))
      return trim( "{$from['first_name']} {$from['last_name']}");

    if (isset($from['first_name']))
      return trim( $from['first_name'] );

    if (isset($from['username']))
      return trim( $from['username'] );

    return 'Unknown';
  }

}