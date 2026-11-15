<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents information about an order.
 * @see https://core.telegram.org/bots/api#orderinfo
 *
 * @method string name() Optional. User name
 * @method string phone_number() Optional. User's phone number
 * @method string email() Optional. User email
 * @method ShippingAddress shipping_address() Optional. User shipping address
 */
class OrderInfo extends Base
{

  protected $wrapper = [
    'shipping_address' => ShippingAddress::class,
  ];

}
