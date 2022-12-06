<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Text parser, my addition to telegram bot API for easy manipulation with text
 * Includes function to read trigger, tokens and lines from string
 */
class Text
{

  protected $text, $data = [];

  public function __construct( string $text )
  {
    $this->data = preg_split("@\s+@imU", $text );
    $this->text = $text;
  }

  public function __toString()
  {
    return $this->text.'';
  }

  public function token($from = 0, $length = null)
  {
    return implode( " ", array_slice($this->data, $from, $length));
  }

  public function line($from = 0, $length = null )
  {
    $text = explode("\n", $this->text);
    return implode( "\n", array_slice($text, $from, $length));
  }

  public function trigger()
  {
    $trigger    = mb_strtolower( $this->token(0,1));
    $first_char = mb_substr( $trigger, 0, 1);

    // remove first char, if it's ! or /
    if (in_array( $first_char, ['!', '/']))
    {
      $trigger = mb_substr($trigger,1);
    }
    return $trigger;
  }

  public function substring( $from, $length = null )
  {
    return mb_substr( $this->text, $from, $length );
  }

  public function length()
  {
    return mb_strlen( $this->text );
  }

  public function contains( $text )
  {
    return preg_match("@\b" . preg_quote($text) . "\b@imu", $this->text) ? true : false;
  }

  public function words()
  {
    return $this->data;
  }

}