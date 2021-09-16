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
use Joker\Parser\Update;

class Uptime extends Base
{

  private $started;

  public function __construct($options = [])
  {
    parent::__construct($options);
    $this->started = time();
  }

  public function onPublicText( Update $update )
  {
    if ($update->message()->text()->trigger() === 'uptime')
    {
      $me     = $update->bot()->getMe();
      $uptime = self::diffTimeInWords($this->started, time() );
      $update->answerMessage( "$me uptime is $uptime" );
      return false;
    }
  }

  /**
   * Returns time difference in words
   * @param $from
   * @param $to
   *
   * @return string
   */
  public static function diffTimeInWords($from,$to)
  {
    $date1 = new DateTime("@$from");
    $date2 = new DateTime("@$to");
    $interval =  date_diff($date1, $date2);
    $result = [];
    foreach (['%y'=>'years', '%m' => 'months', '%d' => 'days', '%h' => 'hours', '%i' => 'minutes', '%s' => 'seconds'] as $key => $value)
      if ($num = $interval->format($key))
        $result[] = "$num $value";

    return implode(" ", $result);
  }

}