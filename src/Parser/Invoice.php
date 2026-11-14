<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains basic information about an invoice.
 * @see https://core.telegram.org/bots/api#invoice
 *
 * @method string title() Product name
 * @method string description() Product description
 * @method string start_parameter() Unique bot deep-linking parameter that can be used to generate this invoice
 * @method string currency() Three-letter ISO 4217 currency code, or “XTR” for payments in Telegram Stars
 * @method integer total_amount() Total price in the smallest units of the currency
 */
class Invoice extends Base
{

  protected $wrapper = [
  ];

}
