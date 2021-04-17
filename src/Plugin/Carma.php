<?php
/**
 * Carma plugin for Joker
 *
 * Allows people to exchange carma between them by like and dislike their posts
 *
 * Options:
 * - `clean_time` (false|integer, optional, default 5)  - false, or seconds to remove carma exchange message
 * - `power_time` (integer, optional, default 600) - number of seconds to have full power (1)
 * - `start_carma` (integer, optional, default 10)  - points you start with
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Event;
use Joker\Parser\Message;
use Joker\Parser\User;
use Joker\Plugin;

class Carma extends Plugin
{

  protected $messages = [];
  protected $users = [];

  /**
   * Reply to /carma command with information (for now only rating available)
   *
   * @param Event $event
   *
   * @return false|void
   */
  public function onPublicText( Event $event )
  {

    $message = $event->getMessage();

    // make local database of usernames/users
    $this->users[ '@' . $message->getFrom()->getUsername() ] = $message->getFrom();

    // debug info
    if ($message->getText()->trigger() === 'carmadebug')
    {
      $answer = [];
      $answer[] = 'Debug carma info:';
      $sum = array_sum( array_map(function ($user) use (&$answer){
        $rating = round( $result = $this->getRating( $user ), 2);
        $power  = round( $this->getPower( $user ), 1 );
        $answer[] = "- $user has $rating carma and $power power";
        return $result;
      }, $this->users));
      $answer[] = "Total: $sum";
      $event->answerMessage( trim( implode(PHP_EOL, $answer)) );
      return false;
    }

    if ($message->getText()->trigger() !== 'carma') return;

    // array of answer
    $answer = [];

    // if message has entities, then search them in known users database
    if ($entities = $message->getEntities())
    {
      // raw text to parse entities from
      $text = $message->getText() .'';

      $answer = array_filter( array_map(function ($entity) use ($text) {
        if ($entity->getType() !== 'mention') return;
        $username = substr( $text, $entity->getOffset(), $entity->getLength());
        if (!isset($this->users[$username])) return;
        $user = $this->users[ $username ];
        $rating = round( $result = $this->getRating( $user ), 2);
        $power  = round( $this->getPower( $user ), 1 );
        return"- $user has $rating carma and $power power";
      }, $entities) );

    }

    // if no answer yet, add current user's carma
    if (!count($answer))
    {
      $user   = $message->getFrom();
      $rating = round( $this->getRating( $user ), 2 );
      $power  = round( $this->getPower( $user ), 1 );
      $answer[] = "$user, you have $rating carma available, your power is $power";
    }

    // add instructions to the end of answer
    $answer[] = "";
    $answer[] = "To give or steal carma, say + or - in reply to anybody's message. " .
                "Amount of carma you exchange, depends on yours and other party powers.";

    $event->answerMessage( trim( implode(PHP_EOL, $answer)) );
    return false;
  }

  /**
   * Clean message after [clean_time] seconds
   * @param Event $event
   *
   */
  public function onEmpty( Event $event )
  {
    if (!$this->getOption('clean_time', 5)) return;

    foreach ($this->messages as $key => $message) /** @var Message $message */
    {
      if (time() >= $message->getDate() + $this->getOption('clean_time', 5))
      {
        $event->getBot()->deleteMessage($message->getChat()->getId(),$message->getMessageId());
        unset($this->messages[$key]);
      }
    }
  }

  /**
   * Reply public chat with + or -
   * @param Event $event
   *
   * @return false|void
   */
  public function onPublicTextReply( Event $event )
  {
    $message  = $event->getMessage();
    $userfrom = $message->getFrom();
    $userto   = $message->getReplyToMessage()->getFrom();

    // cannot share carma with yourself
    if ( $userfrom->getId() === $userto->getId() ) return false;

    // cannot share carma with bot
    if ( $userfrom->isBot() || $userto->isBot() ) return false;

    // get ratings
    $r = [
      'fr' =>[
        'power' => $this->getPower( $userfrom ),
        'old'   => $rating = $this->getRating( $userfrom ),
        'new'   => $rating,
      ],
      'to' => [
        'power' => $this->getPower( $userto ),
        'old'   => $rating = $this->getRating( $userto ),
        'new'   => $rating,
    ]];

    // check first leter, is it + or -
    switch ( $sign = substr( $event->getMessageText(), 0, 1))
    {
      case '+':
        $r['fr']['new'] -= $r['to']['power']; // remove up to 1 carma from 'fr'
        $r['to']['new'] += $r['fr']['power']; // give up to 1 carma to 'to'
        $answer = "%from% gave %amount% carma to %to%.";
        break;
      case '-':
        $r['fr']['new'] += $r['to']['power']; // give up to 1 carma to 'fr'
        $r['to']['new'] -= $r['fr']['power']; // remove up to 1 carma from 'to'
        $answer = "%from% stole %amount% carma from %to%.";
        break;
      default:
        return;
    }

    if ($r['fr']['new'] < 0) // not enough carma
    {
      $answer = "S0rry %from%, not enough carma to do that.";
      $r['fr']['new'] = $r['fr']['old'];
      $r['to']['new'] = $r['to']['old'];
    }
    elseif ($r['to']['new'] < 0)  // not enough carma
    {
      $answer = "%to% has not enough carma to steal.";
      $r['fr']['new'] = $r['fr']['old'];
      $r['to']['new'] = $r['to']['old'];
    }
    else // save ratings
    {
      $this->setRating( $userfrom, $r['fr']['new'] );
      $this->setRating( $userto, $r['to']['new'] );
    }

    // answer
    $answer = strtr( "$answer\n%from% has %newfrom%, %to% has %newto%.\nType !carma to see yours.", [
      '%from%'    => $userfrom,
      '%to%'      => $userto,
      '%amount%'  => round( abs($r['to']['old'] - $r['to']['new']), 2),
      '%newfrom%' => round( $r['fr']['new'], 2),
      '%newto%'   => round( $r['to']['new'], 2),
    ]);

    $this->messages[] = $event->answerMessage( $answer );
    return false;
  }

  /**
   * Calculate power of user (amount of hours, since last change of his rating), maximum 1
   * @param User $user
   *
   * @return float
   */
  private function getPower(User $user ): float
  {
    $file = "data/carma/" . $user->getId();

    // if no rating yet - maximum power
    if (!file_exists( $file )) return 1.0;

    // need to clear stat cache for more accurate result
    clearstatcache( true, $file );

    // 0 seconds = 0, ..., [power_time] seconds = 1
    $power = ( time() - filemtime( $file ) ) / $this->getOption('power_time', 600);
    return $power>1 ? 1.0 : $power;
  }

  /**
   * Get rating of user
   * @param User $user
   *
   * @return float
   */
  private function getRating( User $user ) : float
  {
    $file = "data/carma/" . $user->getId();
    return file_exists( $file )
      ? (float) file_get_contents( $file )
      : (float) $this->getOption('start_carma', 10);
  }

  private function setRating( User $user, $value )
  {
    $file = "data/carma/" . $user->getId();
    if (!file_exists( $dir = dirname( $file ))) mkdir( $dir );
    file_put_contents( $file , $value);
  }

}