<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the position of a rectangle area on a story media or a TON wallet card.
 * @see https://core.telegram.org/bots/api#areaposition
 *
 * @method float x_percentage() The abscissa of the area's center, as a percentage of the media or the card width
 * @method float y_percentage() The ordinate of the area's center, as a percentage of the media or the card height
 * @method float width_percentage() The width of the area's rectangle, as a percentage of the media or the card width
 * @method float height_percentage() The height of the area's rectangle, as a percentage of the media or the card height
 * @method float rotation_angle() The clockwise rotation angle of the rectangle, in degrees; 0-360
 * @method float corner_radius_percentage() The radius of the rectangle corner rounding, as a percentage of the media or the card width
 */
class AreaPosition extends Base
{

  protected $wrapper = [
  ];

}
