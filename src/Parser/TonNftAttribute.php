<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an attribute of an NFT in the TON blockchain.
 * @see https://core.telegram.org/bots/api#tonnftattribute
 *
 * @method string trait_type() Trait type of the attribute
 * @method string value() Value of the attribute
 */
class TonNftAttribute extends Base
{

  protected $wrapper = [
  ];

}
