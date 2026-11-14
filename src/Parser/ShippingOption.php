<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents one shipping option.
 * @see https://core.telegram.org/bots/api#shippingoption
 *
 * @method string id() Shipping option identifier
 * @method string title() Option title
 * @method LabeledPrice[] prices() List of price portions
 */
class ShippingOption extends Base
{

  protected $wrapper = [
    'prices' => LabeledPrice::class,
  ];

}
