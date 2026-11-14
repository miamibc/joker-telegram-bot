<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the model of a unique gift.
 * @see https://core.telegram.org/bots/api#uniquegiftmodel
 *
 * @method string name() Name of the model
 * @method Sticker sticker() The sticker that represents the unique gift
 * @method integer rarity_per_mille() The number of unique gifts that receive this model for every 1000 gift upgrades. Always 0 for crafted gifts.
 * @method string rarity() Optional. Rarity of the model if it is a crafted model. Currently, can be “uncommon”, “rare”, “epic”, or “legendary”.
 */
class UniqueGiftModel extends Base
{

  protected $wrapper = [
    'sticker' => Sticker::class,
  ];

}
