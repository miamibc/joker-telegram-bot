<?php
/**
 * Joker Plugin
 *   Base for Joker Telegram bot
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

abstract class Base
{

  protected $options = [];

  public function __construct( $options = [] )
  {
    $this->options = array_merge($this->options, $options);
  }

  public function getOption( $name, $default = null)
  {
    return isset($this->options[$name]) ? $this->options[$name] : $default;
  }

  public function getOptions()
  {
    return $this->options;
  }
}