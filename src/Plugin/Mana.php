<?php
/**
 * Mana plugin for Joker
 *
 * Allows people to exchange mana between them by like and dislike their posts
 *
 * Options:
 * - `speed` (integer, optional, default 600) - number of seconds to have full power (1)
 * - `start` (integer, optional, default 10)  - points you start with
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Event;
use Joker\Parser\Message;
use Joker\Parser\User;
use Joker\Plugin;

class Mana extends Plugin
{

  protected $messages = [];
  protected $users = [];

  /**
   * Reply to /mana command with information (for now only rating available)
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

    if ($message->getText()->trigger() !== 'mana') return;

    // array of answer
    $answer = [];

    // if message has entities, then search them in known users database
    if ($entities = $message->getEntities())
    {
      // raw text to parse entities from
      $text = $message->getText() .'';

      foreach ( $entities as $entity)
      {
        // only mentions is needed
        if ($entity->getType() !== 'mention') continue;
        $username = substr( $text, $entity->getOffset(), $entity->getLength());

        // if not exists, skip
        if (!isset($this->users[$username])) continue;
        $user   = $this->users[$username];
        $rating = round( $this->getRating( $user ), 2 );
        $power  = round( $this->getPower( $user ), 2 );
        $answer[] = "$user has $rating manas and $power power";
      }
    }

    // otherwise, show current user's mana
    else
    {
      $user   = $message->getFrom();
      $rating = round( $this->getRating( $user ), 2 );
      $power  = round( $this->getPower( $user ), 2 );
      $answer[] = "$user, you have $rating manas available, your power is $power";
    }

    // add instructions to the end of answer
    $answer[] = "";
    $answer[] = "To give or steal mana, say + or - in reply to anybody's message. " .
                "Amount of mana you exchange, depends on yours and other party powers.";

    $event->answerMessage( trim( implode(PHP_EOL, $answer)) );
    return false;
  }

  /**
   * Delete message after [seconds] seconds
   * @param Event $event
   *
   */
  public function onEmpty( Event $event )
  {
    foreach ($this->messages as $key => $message) /** @var Message $message */
    {
      if (time() >= $message->getDate() + $this->getOption('display_message',5))
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

    // cannot share mana with yourself
    if ( $userfrom->getId() === $userto->getId() ) return false;

    // cannot share mana with bot
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
        $r['fr']['new'] -= $r['to']['power']; // remove up to 1 mana from 'fr'
        $r['to']['new'] += $r['fr']['power']; // give up to 1 mana to 'to'
        $answer = "%from% gave %amount% manas to %to%.";
        break;
      case '-':
        $r['fr']['new'] += $r['to']['power']; // give up to 1 mana to 'fr'
        $r['to']['new'] -= $r['fr']['power']; // remove up to 1 mana from 'to'
        $answer = "%from% stole %amount% manas from %to%.";
        break;
      default:
        return;
    }

    if ($r['fr']['new'] < 0) // not enough mana
    {
      $answer = "S0rry %from%, not enough mana to do that.";
    }
    elseif ($r['to']['new'] < 0)  // not enough mana
    {
      $answer = "%to% has not enough mana to steal.";
    }
    else // save ratings
    {
      $this->setRating( $userfrom, $r['fr']['new'] );
      $this->setRating( $userto, $r['to']['new'] );
    }

    // answer
    $answer = strtr( "$answer\n%from% has %newfrom%, %to% has %newto%.\nType !mana to see yours.", [
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
    $file = "data/mana/" . $user->getId();

    // if no rating yet - maximum power
    if (!file_exists( $file )) return 1.0;

    // 0 seconds = 0, ..., [speed] seconds = 1
    $power = ( time() - filemtime( $file ) ) / $this->getOption('speed', 600);
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
    $file = "data/mana/" . $user->getId();
    return file_exists( $file )
      ? (float) file_get_contents( $file )
      : (float) $this->getOption('start', 10);
  }

  private function setRating( User $user, $value )
  {
    $file = "data/mana/" . $user->getId();
    if (!file_exists( $dir = dirname( $file ))) mkdir( $dir );
    file_put_contents( $file , $value);
  }

}