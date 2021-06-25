<?php
/**
 * Joker Log Plugin
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Log extends Base
{

  protected $options = [
    'empty'  => false, // empty event symbol, for example .
    'screen' => false, // show log in screen
    'file'   => false, // log to file
  ];

  public function onEmpty( Update $update )
  {
    if ($this->getOption('empty'))
      echo $this->getOption('empty');
  }

  public function onMessage( Update $update )
  {

    $json = $update->toJson();

    if ($this->getOption('screen'))
    {
      echo $json.PHP_EOL;
    }

    if ($this->getOption('file'))
    {
      file_put_contents( $this->getOption('file'), $json.PHP_EOL, FILE_APPEND );
    }

  }
}