<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a Telegram Star transaction. Note that if the buyer initiates a chargeback with the payment provider from whom they acquired Stars (e.g., Apple, Google) following this transaction, the refunded Stars will be deducted from the bot's balance. This is outside of Telegram's control.
 * @see https://core.telegram.org/bots/api#startransaction
 *
 * @method string id() Unique identifier of the transaction. Coincides with the identifier of the original transaction for refund transactions. Coincides with SuccessfulPayment.telegram_payment_charge_id for successful incoming payments from users
 * @method integer amount() Integer amount of Telegram Stars transferred by the transaction
 * @method integer nanostar_amount() Optional. The number of 1/1000000000 shares of Telegram Stars transferred by the transaction; from 0 to 999999999
 * @method integer date() Date the transaction was created in Unix time
 * @method TransactionPartner source() Optional. Source of an incoming transaction. Only for incoming transactions
 * @method TransactionPartner receiver() Optional. Receiver of an outgoing transaction. Only for outgoing transactions
 */
class StarTransaction extends Base
{

  protected $wrapper = [
    'source' => TransactionPartner::class,
    'receiver' => TransactionPartner::class,
  ];

}
