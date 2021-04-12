<?php
/**
 * Joker Message Text Parser
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class MessageTextParser
{

  public  $data;

  public function __construct( $data)
  {
    $this->data = preg_split("@\s+@imU", $data );
  }

  public function token( $from = 0, $to = null)
  {
    return implode( " ", array_splice($this->data, $from, $to));
  }

  public function trigger()
  {
    $trigger = strtolower( $this->token(0,1));

    if (in_array( substr( $trigger, 0, 1), ['!', '/']))
      $trigger = substr( $trigger, 1);

    return $trigger;
  }
}