<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Text
{

  private $data = [];

  public function __construct( $text )
  {
    $this->data = preg_split("@\s+@imU", $text );
  }

  public function __toString()
  {
    return implode(" ", $this->data);
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