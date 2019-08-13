<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

abstract class Plugin
{

  protected $defaults = [], $options = [];

  public function __construct( $options = [] )
  {
    $this->options = array_merge($this->defaults, $options);
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