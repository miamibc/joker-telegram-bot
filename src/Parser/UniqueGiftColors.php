<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about the color scheme for a user's name, message replies and link previews based on a unique gift.
 * @see https://core.telegram.org/bots/api#uniquegiftcolors
 *
 * @method string model_custom_emoji_id() Custom emoji identifier of the unique gift's model
 * @method string symbol_custom_emoji_id() Custom emoji identifier of the unique gift's symbol
 * @method integer light_theme_main_color() Main color used in light themes; RGB format
 * @method integer[] light_theme_other_colors() List of 1-3 additional colors used in light themes; RGB format
 * @method integer dark_theme_main_color() Main color used in dark themes; RGB format
 * @method integer[] dark_theme_other_colors() List of 1-3 additional colors used in dark themes; RGB format
 */
class UniqueGiftColors extends Base
{

  protected $wrapper = [
  ];

}
