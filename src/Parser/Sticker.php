<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a sticker.
 * @see https://core.telegram.org/bots/api#sticker
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer width() Sticker width
 * @method integer height() Sticker height
 * @method boolean is_animated() True, if the sticker is animated
 * @method PhotoSize thumb() Optional. Sticker thumbnail in the .WEBP or .JPG format
 * @method string emoji() Optional. Emoji associated with the sticker
 * @method string set_name() Optional. Name of the sticker set to which the sticker belongs
 * @method MaskPosition mask_position() Optional. For mask stickers, the position where the mask should be placed
 * @method integer file_size() Optional. File size
 */
class Sticker extends Base
{

  protected $wrapper = [
    'thumb' => PhotoSize::class,
    'mask_position' => MaskPosition::class,
  ];


}