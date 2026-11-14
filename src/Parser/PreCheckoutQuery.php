<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about an incoming pre-checkout query. The bot must answer it within 10 seconds.
 * @see https://core.telegram.org/bots/api#precheckoutquery
 *
 * @method string id() Unique query identifier
 * @method User from() User who sent the query
 * @method string currency() Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
 * @method integer total_amount() Total price in the smallest units of the currency
 * @method string invoice_payload() Bot-specified invoice payload
 * @method string shipping_option_id() Optional. Identifier of the shipping option chosen by the user
 * @method OrderInfo order_info() Optional. Order information provided by the user
 */
class PreCheckoutQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'order_info' => OrderInfo::class,
  ];

  /**
   * Answer this pre-checkout query
   * @param bool $ok true to proceed with the order, false if there are any problems
   * @param string|null $error_message required if $ok is false, human readable reason shown to the user
   *
   * @return bool
   */
  public function answer( bool $ok = true, $error_message = null )
  {
    if (!$bot = $this->bot()) return false;
    $data = ['pre_checkout_query_id' => $this->id(), 'ok' => $ok];
    if (!$ok && $error_message) $data['error_message'] = $error_message;
    return (bool) $bot->customRequest('answerPreCheckoutQuery', $data);
  }

}
