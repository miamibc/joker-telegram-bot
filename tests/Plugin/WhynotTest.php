<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Whynot;
use PHPUnit\Framework\TestCase;

class WhynotTest extends TestCase
{

  public function testGenerate()
  {
    $plugin = new Whynot();
    $this->assertStringContainsString("MyName", $plugin->generate("MyName"));
    $this->assertEquals( 4, substr_count(  $plugin->generate(), '.'));
  }
}
