<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a transfer with a TON wallet.
 * @see https://core.telegram.org/bots/api#tonwallettransfer
 *
 * @method string transaction_id() Unique identifier of the transaction; may be empty if the transfer hasn't been sent yet
 * @method string peer_address() Address of the transfer peer in the TON blockchain
 * @method string amount() The amount of transferred cryptocurrency, in the smallest units of the cryptocurrency, as a decimal number; negative for outgoing transfers
 * @method string comment() Optional. Comment added to the transfer; omitted if the comment is encrypted
 */
class TonWalletTransfer extends Base
{

  protected $wrapper = [
  ];

}
