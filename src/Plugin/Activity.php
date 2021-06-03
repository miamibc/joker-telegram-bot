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

  public function onPublicMessage( Event $event)
  {
    // add messages to the pool
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
      $custom->username         = $user->username() ? $user->username() : null;
      $custom->name             = $user->name() ? $user->name() : null;
      $custom->last_messsage_at = $message->date();
      $custom->last_messsage_id = $message->id();

      if ($message->chat())
        $custom->last_messsage_chat_id = $message->chat()->id();

      if ($message->text())
        $custom->all_text_length = $custom->all_text_length + strlen( $message->text().'' );

      if ($message->caption())
        $custom->all_text_length = $custom->all_text_length + strlen( $message->caption().'' );

      // save customer data to sqlite
      $user->saveCustom();
    }

    // clean pool and sync time
    $this->pool = [];
    $this->sync = false;
  }

  public function onPublicPin( Event $event)
  {
    $message = $event->message();
    $custom = $message->chat()->getCustom();

    $custom->pinned_message_id  = $message->pinned_message()->id();
    $custom->pinned_text = (string) $message->pinned_message()->text();
    $custom->pinned_text_author = $message->pinned_message()->from()->id();

    $message->chat()->saveCustom();
  }


}