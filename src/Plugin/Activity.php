<?php
/**
 * Activity plugin for Joker
 * Tracks users activity
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Activity extends Base
{

  protected $options = [
    'description' => 'Saves publicly available user information, like name, username or ID, into the local database',
    'risk' => 'Medium',
  ];

  private $pool = [];

  /** @var integer */
  private $sync;

  public function onPublicMessage( Update $update )
  {
    // add messages to the pool
    $this->pool[] = $update;
  }

  public function onEmpty( Update $update )
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
    foreach ( $this->pool as $update ) /** @var Update $update */
    {
      $message = $update->message();

      // process only messages having `from`and chat field
      if ( !$user = $message->from() ) continue;
      if ( !$chat = $message->chat() ) continue;

      // get custom data for this user from sqlite
      $userCustom = $user->getCustom();
      $chatCustom = $chat->getCustom();

      // add info
      $userCustom->username         = $user->username() ? $user->username() : null;
      $userCustom->name             = $user->name() ? $user->name() : null;

      $userCustom->last_messsage_at =
      $chatCustom->last_messsage_at = $message->date();

      $userCustom->last_messsage_id =
      $chatCustom->last_messsage_id = $message->id();

      if ($message->chat())
      {
        $userCustom->last_messsage_chat_id =
        $chatCustom->last_messsage_chat_id = $message->chat()->id();
      }

      if ($message->text())
      {
        $userCustom->all_text_length += strlen($message->text().'');
        $chatCustom->all_text_length += strlen($message->text().'');
      }

      if ($message->caption())
      {
        $userCustom->all_text_length += strlen($message->caption().'');
        $chatCustom->all_text_length += strlen($message->caption().'');
      }

      // save customer data to sqlite
      $user->saveCustom();
      $chat->saveCustom();

    }

    // clean pool and sync time
    $this->pool = [];
    $this->sync = false;
  }

  public function onPublicPin( Update $update )
  {
    $message = $update->message();
    $custom = $message->chat()->getCustom();

    $custom->pinned_message_id  = $message->pinned_message()->id();
    $custom->pinned_text = (string) $message->pinned_message()->text();
    $custom->pinned_text_author = $message->pinned_message()->from()->id();

    $message->chat()->saveCustom();
  }


}