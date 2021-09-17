<?php
/**
 * Carma plugin for Joker
 *
 * Allows people to exchange carma between them by like and dislike their posts
 *
 * Options:
 * - `clean_time` (false|integer, optional, default 10)  - false, or seconds to remove carma exchange message
 * - `power_time` (integer, optional, default 600) - number of seconds to have full power (1)
 * - `start_carma` (integer, optional, default 10)  - points you start with
 * - `limit` (integer, optional, default 30)  - number of results in carma top
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Message;
use Joker\Parser\Update;
use Joker\Parser\User;
use RedbeanPHP\R;

class Carma extends Base
{

  protected
    $options = ['clean_time' => false, 'power_time' => 600,'start_carma' => 10, 'limit' => 30], // defaults
    $messages_to_clean = [], // array with messages that must be cleaned
    $users = [];    // array of username/user


  /**
   * Reply to /carma command with information (for now only rating available)
   *
   * @param Update $update
   * @return false|void
   */
  public function onPublicText( Update $update )
  {

    $message = $update->message();
    $userfrom = $message->from();

    // increment rating, 1 per megabyte of text
    $rating = $this->getRating( $userfrom );
    $userfrom->getCustom()->carma_rating = $rating + strlen( $message->text() ) / 1024;
    $userfrom->saveCustom();

    // do not process, if trigger is not carma
    if ($message->text()->trigger() !== 'carma') return;

    // !carma debug, lists all registered users
    if ($message->text()->token(1) === 'debug')
    {

      // only allowed users
      $channels = explode(' ', $userfrom->getCustom()->admin_channels);
      if (!in_array( $message->chat()->name(), $channels)) return;

      $answer = ['Debug carma info:'];
      $sum = array_sum( array_map(function ($user) use (&$answer){
        $rating = round( $result = $this->getRating($user) , 2);
        $power  = round( $this->getPower($user) , 2);
        $name   = $user->name ?? $user->username;
        $answer[] = "- $name has $rating carma and $power power";
        return $result;
      }, R::findAll('user', ' ORDER BY carma_rating DESC')));
      $answer[] = "Total: $sum";
      $update->answerMessage( trim( implode(PHP_EOL, $answer)) );
      return false;
    }

    elseif ($message->text()->token(1) === 'top')
    {
      $answer = ['Carma top:'] + array_map(function ($user) {
        $rating = round( $this->getRating($user) , 2);
        $name   = $user->name ?? $user->username;
        return "- $name has $rating carma";
      }, R::findAll('user', ' ORDER BY carma_rating DESC LIMIT ' . $this->getOption('limit')));
      $update->answerMessage( trim( implode(PHP_EOL, $answer)) );
      return false;
    }

    // array of answer
    $answer = [];

    // if message has entities, then search them in known users database
    if ($entities = $message->entities())
    {
      // raw text to parse entities from
      $text = $message->text().'';

      // extract usernames from entities
      $usernames = array_filter( array_map(function ($entity) use ($text) {
        if ($entity->type() !== 'mention') return;
        $username = substr( $text, $entity->offset(), $entity->length());
        return substr( $username, 1);
      }, $entities));

      // make answer from request to database
      foreach (R::find('user', ' username IN (' . R::genSlots( $usernames ) . ') ORDER BY carma_rating DESC', $usernames) as $user)
      {
        $rating = round( $this->getRating($user) , 2);
        $power  = round( $this->getPower($user), 2);
        $name   = $user->name ?? $user->username;
        $answer[] = "- $name has $rating carma and $power power";
      }

    }

    // if no answer yet, add current user's carma
    if (!count($answer))
    {
      $user   = $message->from();
      $rating = round( $this->getRating( $user ), 2 );
      $power  = round( $this->getPower( $user ), 2 );
      $answer[] = "$user, you have $rating carma available, your power is $power";
    }

    // add instructions to the end of answer
    $answer[] = "";
    $answer[] = "To give or steal carma, say + or - in reply to anybody's message. " .
                "Amount of carma you share, depends on yours and other party powers.";

    $update->answerMessage( trim( implode(PHP_EOL, $answer)) );
    return false;
  }

  /**
   * Reply public chat with + or -
   * @param Update $update
   *
   * @return false|void
   */
  public function onPublicTextReply( Update $update )
  {
    $message  = $update->message();
    $userfrom = $message->from();
    $userto   = $message->reply_to_message()->from();

    // cannot share carma with yourself
    if ($userfrom->id() === $userto->id() ) return false;

    // cannot share carma with bot
    if ( $userfrom->is_bot() || $userto->is_bot() ) return false;

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

    // check first char, is it + or -
    switch ( $sign = substr( $message->text(), 0, 1))
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

    $answer = $update->answerMessage( $answer );

    // if clean_time option is set, put to array
    if ($this->getOption('clean_time'))
    {
      $this->messages_to_clean[] = $answer;
    }

    return false;
  }

  /**
   * Clean message after [clean_time] seconds
   * @param Update $update
   *
   */
  public function onEmpty( Update $update )
  {
    foreach ($this->messages_to_clean as $key => $message) /** @var Message $message */
    {
      if (time() >= $message->date() + $this->getOption('clean_time',10))
      {
        $update->bot()->deleteMessage($message->chat()->id(),$message->message_id());
        unset($this->messages_to_clean[$key]);
      }
    }
  }

  /**
   * Calculate power of user (amount of hours, since last change of his rating), maximum 1
   * @param User|RedBeanPHP\OODBBean $user
   *
   * @return float
   */
  private function getPower( $user ): float
  {
    if ($user instanceof User) $user = $user->getCustom();
    $time = $user->carma_updated;
    $power = is_null($time)
      ? 1.0
      : (time() - $time) / $this->getOption('power_time', 600)
    ;
    return $power > 1 ? 1.0 : $power;
  }

  /**
   * Get rating of user
   * @param User|RedBeanPHP\OODBBean  $user
   *
   * @return float
   */
  private function getRating( $user ) : float
  {
    if ($user instanceof User) $user = $user->getCustom();
    $rating = $user->carma_rating;
    return is_null($rating)
      ? (float) $this->getOption('start_carma', 10)
      : (float) $rating;
  }

  /**
   * Save rating with updated_date
   * @param User|RedBeanPHP\OODBBean $user
   * @param $value
   */
  private function setRating( $user, $value )
  {
    $data = $user instanceof User ? $user->getCustom() : $user;
    $data->carma_rating = $value;
    $data->carma_updated = time();
    if ($user instanceof User) $user->saveCustom();
  }

}