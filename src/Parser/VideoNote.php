<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a video message.
 * @see https://core.telegram.org/bots/api#videonote
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer length() Video width and height (diameter of the video message) as defined by the sender
 * @method integer duration() Duration of the video in seconds as defined by the sender
 * @method PhotoSize thumbnail() Optional. Video thumbnail
 * @method integer file_size() Optional. File size in bytes
 */
class VideoNote extends Base
{

  protected $wrapper = [
    'thumbnail' => PhotoSize::class,
  ];

}
