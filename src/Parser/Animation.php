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
 * @method integer width() Video width as defined by the sender
 * @method integer height() Video height as defined by the sender
 * @method integer duration() Duration of the video in seconds as defined by the sender
 * @method PhotoSize thumbnail() Optional. Animation thumbnail as defined by the sender
 * @method string file_name() Optional. Original animation filename as defined by the sender
 * @method string mime_type() Optional. MIME type of the file as defined by the sender
 * @method integer file_size() Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 * @method PhotoSize thumb() Optional. Animation thumbnail as defined by sender
 */
class Animation extends Base
{

  protected $wrapper = [
    'thumbnail' => PhotoSize::class,
    'thumb' => PhotoSize::class,
  ];


}