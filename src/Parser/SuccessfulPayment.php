<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains basic information about a successful payment. Note that if the buyer initiates a chargeback with the relevant payment provider following this transaction, the funds may be debited from your balance. This is outside of Telegram's control.
 * @see https://core.telegram.org/bots/api#successfulpayment
 *
 * @method string currency() Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
 * @method integer total_amount() Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 * @method string invoice_payload() Bot-specified invoice payload
 * @method integer subscription_expiration_date() Optional. Expiration date of the subscription, in Unix time; for recurring payments only
 * @method bool is_recurring() Optional. True, if the payment is a recurring payment for a subscription
 * @method bool is_first_recurring() Optional. True, if the payment is the first payment for a subscription
 * @method string shipping_option_id() Optional. Identifier of the shipping option chosen by the user
 * @method OrderInfo order_info() Optional. Order information provided by the user
 * @method string telegram_payment_charge_id() Telegram payment identifier
 * @method string provider_payment_charge_id() Provider payment identifier
 */
class SuccessfulPayment extends Base
{

  protected $wrapper = [
    'order_info' => OrderInfo::class,
  ];

}
