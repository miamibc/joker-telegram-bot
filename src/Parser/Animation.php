<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an animation file (GIF or H.264/MPEG-4 AVC video without sound).
 * @see https://core.telegram.org/bots/api#animation
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer width() Video width as defined by sender
 * @method integer height() Video height as defined by sender
 * @method integer duration() Duration of the video in seconds as defined by sender
 * @method PhotoSize thumb() Optional. Animation thumbnail as defined by sender
 * @method string file_name() Optional. Original animation filename as defined by sender
 * @method string mime_type() Optional. MIME type of the file as defined by sender
 * @method integer file_size() Optional. File size
 */
class Animation extends Base
{

  protected $wrapper = [
    'thumb' => PhotoSize::class,
  ];


}