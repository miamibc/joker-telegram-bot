<?php

/**
 * Tick-o-meter, a tool for registering activity in time period
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */
namespace Joker\Helper;

class Tickometer
{

  private $time;
  private $ticks = [];

  public function __construct( $time = 60 )
  {
    $this->time = $time;
  }

  public function tick()
  {
    $this->ticks[] = time();

    if (count($this->ticks) >= 1000)
    {
      // remove 500 elements from begin
      array_splice($this->ticks,0,500);
    }
  }

  /**
   * Clear activity
   */
  public function clear()
  {
    $this->ticks = [];
  }

  /**
   * Count ticks
   * @return int
   */
  public function count()
  {

    $now = time();

    $count = 0;
    foreach ($this->ticks as $time)
    {
      if ($now-$time <= $this->time) $count++;
    }
    return $count;
  }

  /**
   * Ticks per second
   * @return float|int
   */
  public function tps()
  {
    return $this->count() / $this->time;
  }

  /**
   * Seconds per tick
   * @return float|int
   */
  public function spt()
  {
    return $this->time / $this->count();
  }

}