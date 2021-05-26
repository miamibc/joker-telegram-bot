<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the position on faces where a mask should be placed by default.
 * @see https://core.telegram.org/bots/api#maskposition
 *
 * @method string point() The part of the face relative to which the mask should be placed. One of “forehead”, “eyes”, “mouth”, or “chin”.
 * @method float x_shift() Shift by X-axis measured in widths of the mask scaled to the face size, from left to right. For example, choosing -1.0 will place mask just to the left of the default mask position.
 * @method float y_shift() Shift by Y-axis measured in heights of the mask scaled to the face size, from top to bottom. For example, 1.0 will place the mask just below the default mask position.
 * @method float scale() Mask scaling coefficient. For example, 2.0 means double size.
 */
class MaskPosition extends Base
{

  protected $wrapper = [
    'thumb' => PhotoSize::class,
    'mask_position' => MaskPosition::class,
  ];


}