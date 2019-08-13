<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class LogPlugin extends Plugin
{
  protected $defaults = [
    'empty_dots'=>true,
    'screen'=>true,
    'file'=>false,
    'file_buffersize' => 1,
  ];

  private $buffer = [];

  public function onEmpty( Event $update )
  {
    if ($this->options['empty_dots']) echo ".";
  }

  public function onMessage(Event $update)
  {

    $json = $update->toJson();

    if ($this->options['screen'])
    {
      echo PHP_EOL.$json.PHP_EOL;
    }

    if ($this->options['file'])
    {
      $this->buffer[] = $json;
      if (count($this->buffer) >= $this->options['file_buffersize'])
      {
        file_put_contents($this->options['file'],implode(PHP_EOL,$this->buffer).PHP_EOL,FILE_APPEND);
        $this->buffer = [];
      }
    }

  }
}