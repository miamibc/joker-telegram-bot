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
  protected $parent = null;   // parent element
  protected $cache = [];   // wrapped objects cache
  protected $wrapper = []; // array or wrappers

  public function __construct( $data = null, $parent = null )
  {
    $this->data = $data;
    $this->parent = $parent;
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
    $data = $this->data[$key];

    // data is sequental array, result will be array of wrapped elements
    if (is_array( $data ) && array_keys($data) === range(0, count($data) - 1))
    {
      $result = array_map(function ( $item ) use ($wrapper){
        return new $wrapper( $item , $this);
      }, $data);
    }
    // all other types of data, just wrap it
    else
    {
      $result = new $wrapper($data, $this);
    }
    return $this->cache[$key] = $result;
  }

  public function getData()
  {
    return $this->data;
  }

  /**
   * Rewrap data into another class
   * @param string $classname
   *
   * @return mixed
   */
  public function wrapIn( string $classname )
  {
    return new $classname($this->data, $this);
  }

  public function parent()
  {
    return $this->parent;
  }

}