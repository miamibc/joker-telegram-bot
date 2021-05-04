<?php

/**
 * Callback plugin for Joker
 *
 * You can:
 * Add any trigger with any logic to this plugin, then run
 *   !trigger
 *   /trigger
 *   !trigger params params
 *   /trigger params params
 *
 * Constructor $options
 *   trigger  - (required) trigger
 *   callback - (required) callback with Joker\Event parameter
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class Callback extends Plugin
{

  public function onPublicText( Event $event )
  {
    $trigger = strtolower( $this->getOption('trigger') );
    if ($event->message()->text()->trigger() !== $trigger) return;

    // process callback and return it's result
    return call_user_func( $this->getOption('callback'), $event );
  }

}