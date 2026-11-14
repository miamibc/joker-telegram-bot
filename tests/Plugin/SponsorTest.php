<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Parser\Update;
use Joker\Plugin\Sponsor;
use Tests\Bot\FakeBot;
use PHPUnit\Framework\TestCase;

class SponsorTest extends TestCase
{

  public function testKeyboard()
  {
    $plugin = new Sponsor([
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
        ['text' => '⭐ 50', 'callback_data' => 'sponsor:50'],
        ['text' => '⭐ 100', 'callback_data' => 'sponsor:100'],
      ],
    ], $plugin->keyboard());
  }

  public function testNoStars()
  {
    $plugin = new Sponsor(['links' => ['PayPal' => 'https://paypal.me/test'], 'stars' => []]);
    $this->assertSame([[['text' => 'PayPal', 'url' => 'https://paypal.me/test']]], $plugin->keyboard());
  }


  private function payment( $amount, $user = ['id' => 42, 'is_bot' => false, 'first_name' => 'Sergei', 'last_name' => 'M', 'username' => 'miamibc'] )
  {
    return ['update_id' => 1, 'message' => [
      'message_id' => 1, 'date' => 1, 'chat' => ['id' => 42, 'type' => 'private'], 'from' => $user,
      'successful_payment' => ['currency' => 'XTR', 'total_amount' => $amount, 'invoice_payload' => "sponsor:$amount:42",
        'telegram_payment_charge_id' => "tg$amount", 'provider_payment_charge_id' => ''],
    ]];
  }

  private function pay( Sponsor $plugin, FakeBot $bot, $update )
  {
    $bot->updates = [$update];
    $bot->loop();
    [$method, $data] = $bot->last();
    $this->assertSame( 'sendMessage', $method );
    return $data['text'];
  }

  public function testThanksTokens()
  {
    $file = tempnam(sys_get_temp_dir(), 'sponsor');
    $plugin = new Sponsor([
      'file'   => $file,
      'thanks' => '%name% (%username%): %amount% %currency%, total %total% in %count%',
    ]);
    $bot = new FakeBot();
    $bot->plug([$plugin]);

    $this->assertSame( 'Sergei M (@miamibc): 50 ⭐, total 50 in 1', $this->pay($plugin, $bot, $this->payment(50)) );
    $this->assertSame( 'Sergei M (@miamibc): 100 ⭐, total 150 in 2', $this->pay($plugin, $bot, $this->payment(100)) );

    // another user, without username
    $other = ['id' => 7, 'is_bot' => false, 'first_name' => 'Anna'];
    $this->assertSame( 'Anna (Anna): 500 ⭐, total 500 in 1', $this->pay($plugin, $bot, $this->payment(500, $other)) );

    // log has all donations with charge ids for refunds
    $log = array_map('json_decode', file($file));
    $this->assertCount( 3, $log );
    $this->assertSame( 'tg100', $log[1]->telegram_payment_charge_id );
    unlink($file);
  }

  public function testThanksWithoutLogFile()
  {
    $plugin = new Sponsor(['file' => false, 'thanks' => '%amount% of %total%, %count%']);
    $bot = new FakeBot();
    $bot->plug([$plugin]);
    $this->assertSame( '50 of 50, 1', $this->pay($plugin, $bot, $this->payment(50)) );
  }

  public function testOtherPaymentsAreIgnored()
  {
    $plugin = new Sponsor(['file' => false]);
    $bot = new FakeBot();
    $bot->plug([$plugin]);
    $update = $this->payment(50);
    $update['message']['successful_payment']['invoice_payload'] = 'something-else';
    $bot->updates = [$update];
    $bot->loop();
    $this->assertNotContains( 'sendMessage', array_column($bot->requests, 0) );
  }

}
