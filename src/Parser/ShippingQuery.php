<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about an incoming shipping query.
 * @see https://core.telegram.org/bots/api#shippingquery
 *
 * @method string id Unique identifier for this query
 * @method User   from Sender
 * @method string invoice_payload Bot specified invoice payload
 * @method ShippingAddress shipping_address User specified shipping address
 */
class ShippingQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'shipping_address' => ShippingAddress::class,
  ];

}