<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an audio file to be treated as music by the Telegram clients.
 * @see https://core.telegram.org/bots/api#audio
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer duration() Duration of the audio in seconds as defined by the sender
 * @method string performer() Optional. Performer of the audio as defined by the sender or by audio tags
 * @method string title() Optional. Title of the audio as defined by the sender or by audio tags
 * @method string file_name() Optional. Original filename as defined by the sender
 * @method string mime_type() Optional. MIME type of the file as defined by the sender
 * @method integer file_size() Optional. File size in bytes. It can be bigger than 2^31 and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this value.
 * @method PhotoSize thumbnail() Optional. Thumbnail of the album cover to which the music file belongs
 * @method PhotoSize thumb() Optional. Thumbnail of the album cover to which the music file belongs
 */
class Audio extends Base
{

  protected $wrapper = [
    'thumbnail' => PhotoSize::class,
    'thumb' => PhotoSize::class,
  ];


}