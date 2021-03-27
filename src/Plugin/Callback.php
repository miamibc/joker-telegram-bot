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

    $text = $event->getMessageText();
    $text = preg_split('/\s+/', $text, -1, PREG_SPLIT_NO_EMPTY);

    // if trigger exists, and it matches one of versions of triggers...
    if (isset($text[0]) && in_array( strtolower($text[0]), [ "!$trigger", "/$trigger" ] )  )
    {
      // process callback and return it's result
      return call_user_func( $this->getOption('callback'), $event );
    }
  }

}