<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Excuse;
use PHPUnit\Framework\TestCase;

class ExcuseTest extends TestCase
{

  public function testGenerate()
  {
    $plugin = new Excuse();
    $this->assertStringContainsString("MyName", $plugin->generate("MyName"));
    $this->assertEquals( 4, substr_count(  $plugin->generate(), '.'));
  }
}
