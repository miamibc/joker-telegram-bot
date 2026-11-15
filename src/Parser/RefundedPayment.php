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
 * @method string currency() Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars. Currently, always “XTR”.
 * @method integer total_amount() Total refunded price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45, total_amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 * @method string invoice_payload() Bot-specified invoice payload
 * @method string telegram_payment_charge_id() Telegram payment identifier
 * @method string provider_payment_charge_id() Optional. Provider payment identifier
 */
class RefundedPayment extends Base
{

  protected $wrapper = [
  ];

}
