<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes a TON wallet card, which is shown on the profile page of a user to everyone.
 * @see https://core.telegram.org/bots/api#tonwalletcard
 *
 * @method string address() Address of the wallet
 * @method string amount() Balance of the wallet, in the smallest units of the cryptocurrency, as a decimal number
 * @method TonWalletCardSticker[] stickers() Stickers placed on the card
 * @method string background_nft_address() Optional. Address of the NFT of one of the stickers, whose colors must be used for the card background; omitted if the default background must be used
 */
class TonWalletCard extends Base
{

  protected $wrapper = [
    'stickers' => TonWalletCardSticker::class,
  ];

}
