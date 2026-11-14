<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Support;
use PHPUnit\Framework\TestCase;

class SupportTest extends TestCase
{

  public function testKeyboard()
  {
    $plugin = new Support([
      'links' => [
        'GitHub Sponsors' => 'https://github.com/sponsors/test',
        'Stripe'          => '',
        'Ko-fi'           => 'https://ko-fi.com/test',
        'Patreon'         => false,
        'PayPal'          => 'https://paypal.me/test',
      ],
      'stars' => [50, '100', 0],
    ]);

    $this->assertSame([
      [
        ['text' => 'GitHub Sponsors', 'url' => 'https://github.com/sponsors/test'],
        ['text' => 'Ko-fi', 'url' => 'https://ko-fi.com/test'],
      ],
      [
        ['text' => 'PayPal', 'url' => 'https://paypal.me/test'],
      ],
      [
        ['text' => '⭐ 50', 'callback_data' => 'support:50'],
        ['text' => '⭐ 100', 'callback_data' => 'support:100'],
      ],
    ], $plugin->keyboard());
  }

  public function testNoStars()
  {
    $plugin = new Support(['links' => ['PayPal' => 'https://paypal.me/test'], 'stars' => []]);
    $this->assertSame([[['text' => 'PayPal', 'url' => 'https://paypal.me/test']]], $plugin->keyboard());
  }

}
