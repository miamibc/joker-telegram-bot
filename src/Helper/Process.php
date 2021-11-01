<?php

/**
 * Process helper, allows creating query with jobs, running one-by-one
 * with ability to repeat, switch to next item or stop processing any time.
 *
 * Pool - is array of Tasks
 * We get one Task from the Pool and call [start] on it
 * Then, we call [finish] and check the result
 * - result is `repeat` - repeat process with same item
 * - result is `next`   - repeat process with next item in pool
 * - result is `stop`   - stop processing
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Helper;

class Process
{

  private $pool;
  private $current = [];

  public function __construct( array $pool = [] )
  {
    $this->pool = $pool;
  }

  public function add( callable $start, callable $finish )
  {
    $this->pool[] = [
      'start' => $start,
      'finish' => $finish,
    ];
  }

  public function run()
  {

    // no more work, return true
    if (empty($this->pool) && empty($this->current)) return true;

    // empty current, get one from pool and start it
    if (empty($this->current))
    {
      $this->current = array_shift($this->pool);
      call_user_func($this->current['start']);
    }

    // call finish
    $result = call_user_func($this->current['finish']);

    // stop signal - return true
    if ($result === 'stop') return true;

    // next and repeat will return false, but next swiches to next item
    if ($result === 'next') $this->current = [];
    return false;

  }
}