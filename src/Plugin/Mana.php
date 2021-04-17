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
use Joker\Parser\User;
use Joker\Plugin;

class Mana extends Plugin
{

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
    if ($message->getText()->trigger() !== 'mana') return;

    $user = $message->getFrom();
    $rating = round( $this->getRating( $user ), 2 );
    $power  = round( $this->getPower( $user ), 2 );
    $event->answerMessage(
      "$user, you have $rating manas available, your power now is $power.\n\n" .
      "To give or suck mana, say + or - in reply to anybody's message. " .
      "Amount of mana you give or suck, depends on yours and other party powers."
    );
    return false;
  }


  /**
   * Reply public chat with + or -
   * @param Event $event
   *
   * @return false|void
   */
  public function onPublicTextReply( Event $event )
  {
    $message = $event->getMessage();
    $userfrom = $message->getFrom();
    $userto   = $message->getReplyToMessage()->getFrom();

    // cannot share mana with yourself
    if ( $userfrom->getId() === $userto->getId() ) return false;

    // cannot share mana with bot
    if ( $userto->isBot() ) return false;

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
        $answer = "%from% sucked %amount% manas from %to%.";
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
      $answer = "%to% has not enough mana to suck.";
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

    $event->answerMessage( $answer );
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