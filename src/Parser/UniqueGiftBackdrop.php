<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the backdrop of a unique gift.
 * @see https://core.telegram.org/bots/api#uniquegiftbackdrop
 *
 * @method string name() Name of the backdrop
 * @method UniqueGiftBackdropColors colors() Colors of the backdrop
 * @method integer rarity_per_mille() The number of unique gifts that receive this backdrop for every 1000 gifts upgraded
 */
class UniqueGiftBackdrop extends Base
{

  protected $wrapper = [
    'colors' => UniqueGiftBackdropColors::class,
  ];

}
