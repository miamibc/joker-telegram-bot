<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains basic information about a refunded payment.
 * @see https://core.telegram.org/bots/api#refundedpayment
 *
 * @method string currency() Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars. Currently, always “XTR”
 * @method integer total_amount() Total refunded price in the smallest units of the currency
 * @method string invoice_payload() Bot-specified invoice payload
 * @method string telegram_payment_charge_id() Telegram payment identifier
 * @method string provider_payment_charge_id() Optional. Provider payment identifier
 */
class RefundedPayment extends Base
{

  protected $wrapper = [
  ];

}
