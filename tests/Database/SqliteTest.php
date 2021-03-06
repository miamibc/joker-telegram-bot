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

  public function testGetCustom()
  {
    $this->assertInstanceOf( \RedBeanPHP\OODBBean::class , $redis = $this->getCustom() );
    $redis->test = 'ok';
    $this->assertInstanceOf( self::class, $this->saveCustom() );
    $this->assertInstanceOf( self::class, $this->cleanCustom() );
  }

  private function id()
  {
    return 'ok';
  }
}
