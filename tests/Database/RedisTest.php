<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Database;

use Joker\Database\Redis;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-from-github-test
 */
class RedisTest extends TestCase
{

  use Redis;

  public function test()
  {
    $this->assertInstanceOf( \stdClass::class, $redis = $this->getRedis() );
    $redis->test = 'ok';
    $this->assertInstanceOf( self::class, $this->saveRedis() );
    $this->assertInstanceOf( self::class, $this->cleanRedis() );
  }

  private function id()
  {
    return 'ok';
  }
}
