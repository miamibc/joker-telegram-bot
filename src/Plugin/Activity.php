<?php
/**
 * Activity plugin for Joker
 * Tracks users activity
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;
use Joker\Parser\Message;

class Activity extends Plugin
{

  /** @var Message[] */
  private $pool = [];

  /** @var integer */
  private $sync;

  public function onMessage( Event $event)
  {
    // add messages to the pool, if it has from field
    $this->pool[] = $event->message();
  }

  public function onEmpty( Event $event )
  {
    // no sync time, set it
    if (!$this->sync)
    {
      $this->sync = time() + $this->getOption('sync_time', 60); // minute by default
      return;
    }

    // not yet time to sync
    if ( time() < $this->sync)
    {
      return;
    }

    // time to sync, read pool messages
    foreach ( $this->pool as $message ) /** @var Message $message */
    {
      // process only messages having `from` field
      if ( !$user = $message->from() ) continue;

      // get custom data for this user from sqlite
      $custom = $user->getCustom();

      // add info
      $custom->username         = $user->username();
      $custom->name             = $user->name();
      $custom->last_messsage_at = $message->date();
      $custom->last_messsage_id = $message->message_id();

      // save custome data to sqlite
      $user->saveCustom();
    }

    // clean pool and sync time
    $this->pool = [];
    $this->sync = false;
  }

}