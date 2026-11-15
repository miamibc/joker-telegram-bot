<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the symbol shown on the pattern of a unique gift.
 * @see https://core.telegram.org/bots/api#uniquegiftsymbol
 *
 * @method string name() Name of the symbol
 * @method Sticker sticker() The sticker that represents the unique gift
 * @method integer rarity_per_mille() The number of unique gifts that receive this model for every 1000 gifts upgraded
 */
class UniqueGiftSymbol extends Base
{

  protected $wrapper = [
    'sticker' => Sticker::class,
  ];

}
