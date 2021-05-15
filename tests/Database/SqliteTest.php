<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Database;

use Joker\Database\Sqlite;
use PHPUnit\Framework\TestCase;

class SqliteTest extends TestCase
{

  use Sqlite;

  public function test()
  {
    $this->assertInstanceOf( \RedBeanPHP\OODBBean::class , $redis = $this->getCustom() );
    $redis->test = 'ok';
    $this->assertInstanceOf( self::class, $this->saveCustom() );
  }

  private function id()
  {
    return 'ok';
  }
}
