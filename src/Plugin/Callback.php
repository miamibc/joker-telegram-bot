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
 * Options is associative array with keys:trigger and a value:function with Joker\Parser\Update parameter
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Callback extends Base
{

  protected $options = [
    'description' => 'Callback plugin',
    'risk' => 'LOW. Nothing stored by plugin',
  ];

  public function onPublicText( Update $update )
  {
    $triggers = array_keys($this->getOptions());
    $trigger  = $update->message()->text()->trigger();
    if (in_array($trigger, $triggers))
    {
      return call_user_func($this->getOption($trigger),$update);
    }
  }

}