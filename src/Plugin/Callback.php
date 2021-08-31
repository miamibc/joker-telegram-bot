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

use Joker\Parser\Update;

class Callback extends Base
{

  public function onPublicText( Update $update )
  {
    $triggers = array_keys($this->getOptions());
    $trigger  = $update->message()->text()->trigger();
    if (in_array($trigger, $triggers))
    {
      return call_user_func( $this->getOption($trigger), $update );
    }

  }

}