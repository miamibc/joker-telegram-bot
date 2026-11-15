<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an NFT in the TON blockchain.
 * @see https://core.telegram.org/bots/api#tonnft
 *
 * @method string address() Address of the NFT
 * @method string collection_address() Optional. Address of the collection to which the NFT belongs; omitted if unknown
 * @method string index() Index of the NFT among all NFTs from the collection as a decimal number
 * @method string name() Name of the NFT
 * @method string description() Description of the NFT
 * @method TonNftAttribute[] attributes() Optional. Attributes of the NFT
 * @method UniqueGift gift() Optional. The unique gift corresponding to the NFT
 */
class TonNft extends Base
{

  protected $wrapper = [
    'attributes' => TonNftAttribute::class,
    'gift' => UniqueGift::class,
  ];

}
