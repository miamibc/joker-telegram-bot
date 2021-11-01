<?php

/**
 * Timer for doing things with delay
 *
 * - Add tasks (callable, this can be anonymous function) to the timer
 * - Run periodically
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */
namespace Joker\Helper;

class Timer
{

  private $jobs = [];

  /**
   * Add a job
   * @param int $delay Delay in seconds
   * @param callable $job function(){ ... }
   */
  public function add( int $delay, callable $job )
  {
    $this->jobs[] = [ time()+$delay, $job ];

    /* // sorting can be added later
       usort( $this->jobs, function ($a,$b){
         if ($a[0] == $b[0]) return 0;
         return $a[0] < $b[0] ? -1 : 1;
       });
     */
  }

  /**
   * Run timer
   * Proper tasks will be executed and removed
   */
  public function run()
  {
    $time = time();
    foreach ($this->jobs as $k=>$job)
    {
      if ($time > $job[0] && is_callable($job[1]))
      {
        call_user_func( $job[1] );
        unset($this->jobs[$k]);
      }
    }
  }

}