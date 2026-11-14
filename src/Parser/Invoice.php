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
 * @method integer total_amount() Total price in the smallest units of the currency (integer, not float/double). For example, for a price of US$ 1.45 pass amount = 145. See the exp parameter in currencies.json, it shows the number of digits past the decimal point for each currency (2 for the majority of currencies).
 */
class Invoice extends Base
{

  protected $wrapper = [
  ];

}
