<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the type of a background. Currently, it can be one of
 * Currently can be one of BackgroundTypeFill, BackgroundTypeWallpaper, BackgroundTypePattern, BackgroundTypeChatTheme — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#backgroundtype
 *
 * @method string type() Type of the background, always “fill”
 * @method BackgroundFill fill() BackgroundTypeFill, BackgroundTypePattern only. The background fill
 * @method integer dark_theme_dimming() BackgroundTypeFill, BackgroundTypeWallpaper only. Dimming of the background in dark themes, as a percentage; 0-100
 * @method Document document() BackgroundTypeWallpaper, BackgroundTypePattern only. Document with the wallpaper
 * @method bool is_blurred() BackgroundTypeWallpaper only. Optional. True, if the wallpaper is downscaled to fit in a 450x450 square and then box-blurred with radius 12
 * @method bool is_moving() BackgroundTypeWallpaper, BackgroundTypePattern only. Optional. True, if the background moves slightly when the device is tilted
 * @method integer intensity() BackgroundTypePattern only. Intensity of the pattern when it is shown above the filled background; 0-100
 * @method bool is_inverted() BackgroundTypePattern only. Optional. True, if the background fill must be applied only to the pattern itself. All other pixels are black in this case. For dark themes only.
 * @method string theme_name() BackgroundTypeChatTheme only. Name of the chat theme, which is usually an emoji
 */
class BackgroundType extends Base
{

  protected $wrapper = [
    'fill' => BackgroundFill::class,
    'document' => Document::class,
  ];

}
