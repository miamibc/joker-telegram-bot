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
 * @method string id() Unique query identifier
 * @method User from() User who sent the query
 * @method string invoice_payload() Bot-specified invoice payload
 * @method ShippingAddress shipping_address() User specified shipping address
 */
class ShippingQuery extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'shipping_address' => ShippingAddress::class,
  ];

  /**
   * Answer this shipping query
   * @param bool $ok true if delivery to the specified address is possible
   * @param array $shipping_options required if $ok is true, array of ShippingOption arrays
   * @param string|null $error_message required if $ok is false, human readable reason shown to the user
   *
   * @return bool
   */
  public function answer( bool $ok = true, array $shipping_options = [], $error_message = null )
  {
    if (!$bot = $this->bot()) return false;
    $data = ['shipping_query_id' => $this->id(), 'ok' => $ok];
    if ($ok) $data['shipping_options'] = $shipping_options;
    elseif ($error_message) $data['error_message'] = $error_message;
    return (bool) $bot->customRequest('answerShippingQuery', $data);
  }

}