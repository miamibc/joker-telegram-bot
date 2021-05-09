<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;


class Base
{

  protected $data = [];    // incoming data array
  protected $cache = [];   // wrapped objects cache
  protected $wrapper = []; // array or wrappers

  public function __construct( $data )
  {
    $this->data = $data;
  }

  /**
   * Get data, wrapped if possible
   * @param $key
   *
   * @return false|mixed
   */
  public function __call($key, $arguments)
  {
    // no data with this key, return false
    if (!isset($this->data[$key])) return false;

    // no wrapper for this key, return data no need to cache
    if (!isset($this->wrapper[$key])) return $this->data[$key];

    // cache exists, return it
    if (isset($this->cache[$key])) return $this->cache[$key];

    // wrap and save to cache
    $wrapper = $this->wrapper[$key];
    return $this->cache[$key] = new $wrapper($this->data[$key]);
  }

  public function getData()
  {
    return $this->data;
  }

}