<?php
/**
 * Joker Uptime Plugin
 * Shows number of seconds bot was up.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use DateTime;
use Joker\Helper\Strings;
use Joker\Parser\Update;

class Uptime extends Base
{

  protected $options = [
    'description' => 'Uptime information',
    'risk' => 'LOW. Nothing stored by plugin',
  ];

  private $started;

  public function init()
  {
    $this->started = time();
  }

  public function onPublicText( Update $update )
  {
    if ($update->message()->text()->trigger() === 'uptime')
    {
      $me     = $update->bot()->getMe();
      $uptime = Strings::diffTimeInWords($this->started, time() );
      $update->answerMessage( "$me uptime is $uptime" );
      return false;
    }
  }

}