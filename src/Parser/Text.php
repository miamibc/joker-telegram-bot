<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Text
{

  protected $text = "", $data = [];

  public function __construct( $text )
  {
    $this->data = preg_split("@\s+@imU", $text );
    $this->text = $text;
  }

  public function __toString()
  {
    return $this->text;
  }

  public function token( $from = 0, $to = null)
  {
    return implode( " ", array_slice($this->data, $from, $to));
  }

  public function line( $from, $to = null )
  {
    $text = explode("\n", $this->text);
    return implode( "\n", array_slice($text, $from, $to));
  }

  public function trigger()
  {
    $trigger    = strtolower( $this->token(0,1));
    $first_char = substr( $trigger, 0, 1);

    // remove first char, if it's ! or /
    if (in_array( $first_char, ['!', '/']))
      $trigger = substr( $trigger, 1);

    return $trigger;
  }

}