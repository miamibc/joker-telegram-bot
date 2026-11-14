<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a video file of a specific quality.
 * @see https://core.telegram.org/bots/api#videoquality
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer width() Video width
 * @method integer height() Video height
 * @method string codec() Codec that was used to encode the video, for example, “h264”, “h265”, or “av01”
 * @method integer file_size() Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 */
class VideoQuality extends Base
{

  protected $wrapper = [
  ];

}
