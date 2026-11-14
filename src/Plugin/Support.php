<?php
/**
 * Support plugin for Joker
 *
 * Lets people support the bot with a donation:
 *   /support     show ways to donate: external links (GitHub Sponsors, Stripe, Ko-fi, Patreon, PayPal)
 *                and buttons to donate Telegram Stars right in the chat
 *   /paysupport  help with payment issues (required by Telegram for bots accepting Stars)
 *   /terms       terms of donations (required by Telegram for bots accepting payments)
 *
 * Donations inside Telegram are digital, so they are made in Telegram Stars (XTR) only,
 * third-party providers are not allowed for digital goods and services.
 * @see https://core.telegram.org/bots/payments-stars
 *
 * Options:
 * - links       array, title => url of external donation pages, empty urls are skipped
 * - stars       array of integers, amounts of Telegram Stars to offer, empty array disables Stars
 * - text        message shown on /support
 * - title       invoice title, 1-32 characters
 * - invoice     invoice description, 1-255 characters
 * - thanks      message after successful donation, %d is replaced with the number of Stars
 * - paysupport  message shown on /paysupport
 * - terms       message shown on /terms
 * - file        file to log successful donations (with charge id, needed for refunds), false to disable
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Support extends Base
{

  const PAYLOAD = 'support';

  protected $options = [
    'description' => 'Support Joker with a donation',
    'risk' => 'LOW. Successful donations are logged with user id and payment id, to be able to refund them',

    'links' => [
      'GitHub Sponsors' => '',
      'Stripe'          => '',
      'Ko-fi'           => '',
      'Patreon'         => '',
      'PayPal'          => '',
    ],
    'stars' => [50, 100, 500],
    'text' => "Joker has been entertaining chats since 2001. If you like him, you can support his development. Thank you! 🙏",
    'title' => 'Support Joker',
    'invoice' => 'Donation to Joker Telegram Bot development. Thank you for your support!',
    'thanks' => "Thank you for %d ⭐! Joker is grateful 🙏",
    'paysupport' => "Problems with a donation? Write to the bot owner, we will sort it out and refund if needed.",
    'terms' => "Donations are voluntary and give no goods, services or privileges in return. Donations made by mistake can be refunded via /paysupport.",
    'file' => 'data/support.log',
  ];

  /**
   * Commands /support, /paysupport and /terms
   */
  public function onText( Update $update )
  {
    // /support@JokerBot → support
    $trigger = explode('@', $update->message()->text()->trigger())[0];

    switch ($trigger)
    {
      case 'support':
      case 'donate':
        $update->answerMessage( $this->getOption('text'), ['reply_markup' => ['inline_keyboard' => $this->keyboard()]] );
        return false;

      case 'paysupport':
        $update->answerMessage( $this->getOption('paysupport') );
        return false;

      case 'terms':
        $update->answerMessage( $this->getOption('terms') );
        return false;
    }
  }

  /**
   * Stars button pressed: send invoice to the chat
   */
  public function onCallback( Update $update )
  {
    $query = $update->callback_query();
    $data  = explode(':', (string) $query->data());
    if ($data[0] !== self::PAYLOAD) return;

    $amount = isset($data[1]) ? (int) $data[1] : 0;
    if (!in_array($amount, $this->stars(), true) || !$query->message())
    {
      $query->answer(['text' => 'This donation is not available anymore']);
      return false;
    }

    $query->answer([]);
    $this->bot()->sendInvoice(
      $query->message()->chat()->id(),
      $this->getOption('title'),
      $this->getOption('invoice'),
      self::PAYLOAD . ":$amount:" . $query->from()->id(),
      'XTR',
      [['label' => $this->getOption('title'), 'amount' => $amount]],
      ['provider_token' => '']
    );
    return false;
  }

  /**
   * Confirm checkout of our invoices, must be answered within 10 seconds
   */
  public function onCheckout( Update $update )
  {
    $query = $update->pre_checkout_query();
    if (!$this->isOurs( $query->invoice_payload() )) return;

    $ok = $query->currency() === 'XTR' && in_array( (int) $query->total_amount(), $this->stars(), true);
    $query->answer( $ok, $ok ? null : 'This donation is not available anymore, please try /support again');
    return false;
  }

  /**
   * Donation received
   */
  public function onPayment( Update $update )
  {
    $payment = $update->message()->successful_payment();
    if (!$this->isOurs( $payment->invoice_payload() )) return;

    $user = $update->message()->from();
    if ($file = $this->getOption('file'))
    {
      file_put_contents( $file, json_encode([
        'date'   => date('Y-m-d H:i:s'),
        'user'   => $user ? $user->id() : null,
        'name'   => $user ? trim($user->first_name() . ' ' . $user->last_name()) : null,
        'amount' => $payment->total_amount(),
        'currency' => $payment->currency(),
        'telegram_payment_charge_id' => $payment->telegram_payment_charge_id(),
      ], JSON_UNESCAPED_UNICODE) . PHP_EOL, FILE_APPEND);
    }

    $update->answerMessage( sprintf( $this->getOption('thanks'), $payment->total_amount() ) );
    return false;
  }

  /**
   * Inline keyboard: external links two in a row, then Stars buttons in one row
   * @return array
   */
  public function keyboard(): array
  {
    $links = [];
    foreach ((array) $this->getOption('links', []) as $title => $url)
    {
      if ($url) $links[] = ['text' => $title, 'url' => $url];
    }
    $keyboard = array_chunk($links, 2);

    $stars = array_map(function ($amount) {
      return ['text' => "⭐ $amount", 'callback_data' => self::PAYLOAD . ":$amount"];
    }, $this->stars());
    if ($stars) $keyboard[] = $stars;

    return $keyboard;
  }

  /**
   * Allowed amounts of Stars
   * @return int[]
   */
  private function stars(): array
  {
    return array_values( array_filter( array_map('intval', (array) $this->getOption('stars', [])) ) );
  }

  private function isOurs( $payload ): bool
  {
    return explode(':', (string) $payload)[0] === self::PAYLOAD;
  }

}
