<?php

namespace Joker\Helper;

class Strings
{

  /**
   * @param \DateTime|string $datetime
   * @param false $full short or long version
   *
   * @return string
   */
  public static function timeElapsed( $datetime, $full = false)
  {
    $now = new \DateTime;
    $ago = is_string($datetime) ? new \DateTime($datetime) : $datetime;
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
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
    $date1 = new \DateTime("@$from");
    $date2 = new \DateTime("@$to");
    $interval =  date_diff($date1, $date2);
    $result = [];
    foreach (['%y'=>'years', '%m' => 'months', '%d' => 'days', '%h' => 'hours', '%i' => 'minutes', '%s' => 'seconds'] as $key => $value)
      if ($num = $interval->format($key))
        $result[] = "$num $value";

    return implode(" ", $result);
  }

}