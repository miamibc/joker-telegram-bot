<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Bot;
use Joker\Parser\Invoice;
use Joker\Parser\OrderInfo;
use Joker\Parser\PaidMediaPurchased;
use Joker\Parser\PreCheckoutQuery;
use Joker\Parser\RefundedPayment;
use Joker\Parser\ShippingAddress;
use Joker\Parser\StarTransaction;
use Joker\Parser\StarTransactions;
use Joker\Parser\SuccessfulPayment;
use Joker\Parser\TransactionPartner;
use Joker\Parser\Update;
use Joker\Parser\User;
use PHPUnit\Framework\TestCase;

class PaymentsTest extends TestCase
{

  public function testPreCheckoutQuery()
  {
    $update = new Update(['update_id' => 1, 'pre_checkout_query' => [
      'id' => 'q1',
      'from' => ['id' => 42, 'first_name' => 'Test'],
      'currency' => 'XTR',
      'total_amount' => 100,
      'invoice_payload' => 'sponsor:100:42',
      'order_info' => ['name' => 'Test', 'shipping_address' => ['country_code' => 'EE']],
    ]], new Bot(false));

    $this->assertTrue( $update->hasTag('Checkout') );
    $this->assertFalse( $update->hasTag('Message') );
    $this->assertInstanceOf( PreCheckoutQuery::class, $query = $update->pre_checkout_query() );
    $this->assertInstanceOf( User::class, $query->from() );
    $this->assertSame( 42, $query->from()->id() );
    $this->assertSame( 'XTR', $query->currency() );
    $this->assertSame( 100, $query->total_amount() );
    $this->assertInstanceOf( OrderInfo::class, $query->order_info() );
    $this->assertInstanceOf( ShippingAddress::class, $query->order_info()->shipping_address() );
    $this->assertInstanceOf( Bot::class, $query->bot() );
  }

  public function testMessagePayments()
  {
    $update = new Update(['update_id' => 2, 'message' => [
      'message_id' => 1, 'chat' => ['id' => 42, 'type' => 'private'],
      'successful_payment' => [
        'currency' => 'XTR', 'total_amount' => 50, 'invoice_payload' => 'sponsor:50:42',
        'telegram_payment_charge_id' => 'tg1', 'provider_payment_charge_id' => '',
      ],
    ]]);
    $this->assertTrue( $update->hasTag('Payment') );
    $this->assertInstanceOf( SuccessfulPayment::class, $payment = $update->message()->successful_payment() );
    $this->assertSame( 'tg1', $payment->telegram_payment_charge_id() );
    $this->assertFalse( $payment->order_info() );

    $update = new Update(['update_id' => 3, 'message' => [
      'message_id' => 2, 'chat' => ['id' => 42, 'type' => 'private'],
      'refunded_payment' => ['currency' => 'XTR', 'total_amount' => 50, 'invoice_payload' => 'sponsor:50:42', 'telegram_payment_charge_id' => 'tg1'],
    ]]);
    $this->assertTrue( $update->hasTag('Refund') );
    $this->assertInstanceOf( RefundedPayment::class, $update->message()->refunded_payment() );

    $update = new Update(['update_id' => 4, 'message' => [
      'message_id' => 3, 'chat' => ['id' => 42, 'type' => 'private'],
      'invoice' => ['title' => 'T', 'description' => 'D', 'start_parameter' => '', 'currency' => 'XTR', 'total_amount' => 50],
    ]]);
    $this->assertTrue( $update->hasTag('Invoice') );
    $this->assertInstanceOf( Invoice::class, $update->message()->invoice() );
  }

  public function testPaidMediaPurchased()
  {
    $update = new Update(['update_id' => 5, 'purchased_paid_media' => ['from' => ['id' => 42], 'paid_media_payload' => 'p']]);
    $this->assertTrue( $update->hasTag('Purchase') );
    $this->assertInstanceOf( PaidMediaPurchased::class, $update->purchased_paid_media() );
    $this->assertInstanceOf( User::class, $update->purchased_paid_media()->from() );
  }

  public function testStarTransactions()
  {
    $list = new StarTransactions(['transactions' => [
      ['id' => 't1', 'amount' => 100, 'date' => 1, 'source' => ['type' => 'user', 'transaction_type' => 'invoice_payment', 'user' => ['id' => 42]]],
      ['id' => 't2', 'amount' => 50, 'date' => 2, 'receiver' => ['type' => 'fragment', 'withdrawal_state' => ['type' => 'pending']]],
    ]]);
    $this->assertCount( 2, $items = $list->transactions() );
    $this->assertInstanceOf( StarTransaction::class, $items[0] );
    $this->assertInstanceOf( TransactionPartner::class, $items[0]->source() );
    $this->assertSame( 42, $items[0]->source()->user()->id() );
    $this->assertSame( 'pending', $items[1]->receiver()->withdrawal_state()->type() );
  }

}
