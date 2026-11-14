<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the background of a gift.
 * @see https://core.telegram.org/bots/api#giftbackground
 *
 * @method integer center_color() Center color of the background in RGB format
 * @method integer edge_color() Edge color of the background in RGB format
 * @method integer text_color() Text color of the background in RGB format
 */
class GiftBackground extends Base
{

  protected $wrapper = [
  ];

}
