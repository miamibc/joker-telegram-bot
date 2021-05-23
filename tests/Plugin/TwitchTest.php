<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Twitch;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-from-github-test
 */
class TwitchTest extends TestCase
{

  public function test()
  {
    $plugin = new Twitch();
    $result = $plugin->searchChannels('quake');
    $this->assertNotEmpty( $result);
  }
}
