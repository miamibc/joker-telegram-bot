<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the way a background is filled based on the selected colors. Currently, it can be one of
 * Currently can be one of BackgroundFillSolid, BackgroundFillGradient, BackgroundFillFreeformGradient — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#backgroundfill
 *
 * @method string type() Type of the background fill, always “solid”
 * @method integer color() BackgroundFillSolid only. The color of the background fill in the RGB24 format
 * @method integer top_color() BackgroundFillGradient only. Top color of the gradient in the RGB24 format
 * @method integer bottom_color() BackgroundFillGradient only. Bottom color of the gradient in the RGB24 format
 * @method integer rotation_angle() BackgroundFillGradient only. Clockwise rotation angle of the background fill in degrees; 0-359
 * @method integer[] colors() BackgroundFillFreeformGradient only. A list of the 3 or 4 base colors that are used to generate the freeform gradient in the RGB24 format
 */
class BackgroundFill extends Base
{

  protected $wrapper = [
  ];

}
