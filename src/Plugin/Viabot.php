<?php
/**
 * Viabot plugin for Joker
 *
 * Blocks processing of messages sent via bot
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Viabot extends Base
{

  protected $options = [
    'description' => 'Viabot plugin disables processing of messages sent via bots',
    'risk' => 'LOW. Nothing stored by plugin',
  ];

  public function onViabot( Update $update )
  {
    return false;
  }

}