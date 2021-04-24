<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Twitch;
use PHPUnit\Framework\TestCase;

class TwitchTest extends TestCase
{

  public function test()
  {

    $plugin = new Twitch();

    $this->assertSame( 'cub5u3j7it7d6rmncrbg7hszs9j09d', getenv('TWITCH_CLIENT_ID'));

    $result = $plugin->searchChannels('quake');

    $this->assertSame("", $result);


  }
}
