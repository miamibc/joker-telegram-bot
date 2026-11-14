<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes a sticker on a TON wallet card.
 * @see https://core.telegram.org/bots/api#tonwalletcardsticker
 *
 * @method AreaPosition position() Position of the sticker on the card
 * @method TonNft nft() The NFT shown as the sticker
 */
class TonWalletCardSticker extends Base
{

  protected $wrapper = [
    'position' => AreaPosition::class,
    'nft' => TonNft::class,
  ];

}
