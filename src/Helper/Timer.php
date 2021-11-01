<?php

namespace Joker\Helper;

class Timer
{

  private $jobs = [];

  public function add( $delay, callable $job )
  {
    $this->jobs[] = [ time()+$delay, $job ];

    /* // if you need to add sorting
       usort( $this->jobs, function ($a,$b){
         if ($a[0] == $b[0]) return 0;
         return $a[0] < $b[0] ? -1 : 1;
       });
     */
  }

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