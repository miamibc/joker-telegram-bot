<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Database;

use RedisClient\RedisClient;

trait Redis
{

  private $redis_cache;

  private function getRedisKey()
  {
    $class = explode('\\', get_class( $this ) );
    $class = strtolower(end($class));
    return "joker/$class/". $this->id();
  }

  public function getRedis()
  {
    // is already loaded, return it
    if (!is_null($this->redis_cache))
      return $this->redis_cache;

    $class = explode('\\', get_class( $this ) );
    $class = strtolower(end($class));

    $client = new RedisClient();
    $json = $client->get("joker/$class/". $this->id());
    $object = is_null($json) ? new \stdClass() : json_decode( $json );
    return $this->redis_cache = $object;
  }

  public function saveRedis()
  {
    if (!is_null($this->redis_cache))
    {
      $class = explode('\\', get_class( $this ) );
      $class = strtolower(end($class));

      $client = new RedisClient();
      $client->set( $this->getRedisKey() , json_encode($this->redis_cache));
    }
    return $this;
  }

  public function cleanRedis()
  {
    $client = new RedisClient();
    $client->del( $this->getRedisKey() );
    return $this;
  }

}