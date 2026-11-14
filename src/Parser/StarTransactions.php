<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Contains a list of Telegram Star transactions.
 * @see https://core.telegram.org/bots/api#startransactions
 *
 * @method StarTransaction[] transactions() The list of transactions
 */
class StarTransactions extends Base
{

  protected $wrapper = [
    'transactions' => StarTransaction::class,
  ];

}
