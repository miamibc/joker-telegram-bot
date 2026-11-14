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
 * @method string type() Type of the sticker, currently one of “regular”, “mask”, “custom_emoji”. The type of the sticker is independent from its format, which is determined by the fields is_animated and is_video.
 * @method integer width() Sticker width
 * @method integer height() Sticker height
 * @method bool is_animated() True, if the sticker is animated
 * @method bool is_video() True, if the sticker is a video sticker
 * @method PhotoSize thumbnail() Optional. Sticker thumbnail in the .WEBP or .JPG format
 * @method string emoji() Optional. Emoji associated with the sticker
 * @method string set_name() Optional. Name of the sticker set to which the sticker belongs
 * @method File premium_animation() Optional. For premium regular stickers, premium animation for the sticker
 * @method MaskPosition mask_position() Optional. For mask stickers, the position where the mask should be placed
 * @method string custom_emoji_id() Optional. For custom emoji stickers, unique identifier of the custom emoji
 * @method bool needs_repainting() Optional. True, if the sticker must be repainted to a text color in messages, the color of the Telegram Premium badge in emoji status, white color on chat photos, or another appropriate color in other places
 * @method integer file_size() Optional. File size in bytes
 * @method PhotoSize thumb() Optional. Sticker thumbnail in the .WEBP or .JPG format
 */
class Sticker extends Base
{

  protected $wrapper = [
    'thumbnail' => PhotoSize::class,
    'premium_animation' => File::class,
    'mask_position' => MaskPosition::class,
    'thumb' => PhotoSize::class,
  ];


}