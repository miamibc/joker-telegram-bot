<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the options used for link preview generation.
 * @see https://core.telegram.org/bots/api#linkpreviewoptions
 *
 * @method bool is_disabled() Optional. True, if the link preview is disabled
 * @method string url() Optional. URL to use for the link preview. If empty, then the first URL found in the message text will be used.
 * @method bool prefer_small_media() Optional. True, if the media in the link preview is supposed to be shrunk; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method bool prefer_large_media() Optional. True, if the media in the link preview is supposed to be enlarged; ignored if the URL isn't explicitly specified or media size change isn't supported for the preview
 * @method bool show_above_text() Optional. True, if the link preview must be shown above the message text; otherwise, the link preview will be shown below the message text
 */
class LinkPreviewOptions extends Base
{

  protected $wrapper = [
  ];

}
