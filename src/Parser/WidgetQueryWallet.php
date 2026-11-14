<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes a TON wallet of the user, which is used in a query from a widget.
 * @see https://core.telegram.org/bots/api#widgetquerywallet
 *
 * @method string address() Address of the wallet
 * @method string amount() Balance of the wallet, in the smallest units of the cryptocurrency, as a decimal number
 * @method string public_key() Optional. Hex-encoded public key of the wallet; omitted if unknown
 * @method string state_init() Optional. Base64-encoded serialized StateInit of the wallet contract; omitted if and only if public_key is omitted
 */
class WidgetQueryWallet extends Base
{

  protected $wrapper = [
  ];

}
