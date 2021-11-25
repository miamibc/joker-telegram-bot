<?php

/**
 * Interval for doing things with interval
 *
 * - Add tasks (callable, this can be anonymous function)
 * - Run periodically
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */
namespace Joker\Helper;

class Interval
{

  private $jobs = [];

  /**
   * Add a job
   * @param int $delay Delay in seconds
   * @param callable $job function(){ ... }
   */
  public function add( int $delay, callable $job )
  {
    $this->jobs[] = [ $delay, $job, time()+$delay ];
  }

  /**
   * Run timer
   * Proper tasks will be executed and removed
   */
  public function run()
  {
    $time = time();
    foreach ($this->jobs as $job)
    {
      list($delay, $callable, $alarma) = $job;
      if ($time >= $alarma)
      {
        call_user_func( $callable );
        $this->jobs[] = [ $delay, $job, time()+$delay];
      }
    }
  }

}