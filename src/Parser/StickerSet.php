<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a sticker set.
 * @see https://core.telegram.org/bots/api#stickerset
 *
 * @method string name() Sticker set name
 * @method string title() Sticker set title
 * @method string sticker_type() Type of stickers in the set, currently one of “regular”, “mask”, “custom_emoji”
 * @method Sticker[] stickers() List of all set stickers
 * @method PhotoSize thumbnail() Optional. Sticker set thumbnail in the .WEBP, .TGS, or .WEBM format
 */
class StickerSet extends Base
{

  protected $wrapper = [
    'stickers' => Sticker::class,
    'thumbnail' => PhotoSize::class,
  ];

}
