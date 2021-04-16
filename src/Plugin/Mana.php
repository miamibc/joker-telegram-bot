<?php
/**
 * Mana plugin for Joker
 *
 * Allows people to exchange mana between them by like and dislike their posts
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
    $message = $event->getMessage();
    if ($message->getText()->trigger() !== 'me') return;

    $rating = $this->getRating( $message->getFrom()->getId() );
    $event->answerMessage("Mana " . $rating);
    return false;
  }


  public function onPublicTextReply( Event $event )
  {
    $message = $event->getMessage();

    // check all required fields exists in request
    // if (!isset($message['from']['id'], $message['reply_to_message']['from']['id'])) return;

    // get information about authors of both messages
    $from_id   = $message->getFrom()->getId();
    $to_id     = $message->getReplyToMessage()->getFrom()->getId();
    $from_name = $message->getFrom()->getName();
    $to_name   = $message->getReplyToMessage()->getFrom()->getName();

    // get ratings
    $rating = [
      'from' => $this->getRating($from_id),
      'to'   => $this->getRating($to_id),
    ];

    // check first leter, is it + or -
    switch (substr( $event->getMessageText(), 0, 1))
    {
      case '+':
        $rating['from_new'] = $rating['from']-.5;
        $rating['to_new'] = $rating['to']+1;
        $rating['action'] = "$from_name ({$rating['from']}-.5) shared mana with $to_name ({$rating['to']}+1)";
        break;
      case '-':
        $rating['from_new'] = $rating['from']+.5;
        $rating['to_new'] = $rating['to']-1;
        $rating['action'] = "$from_name ({$rating['from']}+.5) sucked mana from $to_name ({$rating['to']}-1)";
        break;
      default:
        return;
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
    if ($from_id == $to_id)
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

}