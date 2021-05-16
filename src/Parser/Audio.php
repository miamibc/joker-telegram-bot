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
 * @see https://core.telegram.org/bots/api#audio
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer duration() Duration of the audio in seconds as defined by sender
 * @method string performer() Optional. Performer of the audio as defined by sender or by audio tags
 * @method string title() Optional. Title of the audio as defined by sender or by audio tags
 * @method string file_name() Optional. Original filename as defined by sender
 * @method string mime_type() Optional. MIME type of the file as defined by sender
 * @method integer file_size() Optional. File size
 * @method PhotoSize thumb() Optional. Thumbnail of the album cover to which the music file belongs
 */
class Audio extends Base
{

  protected $wrapper = [
    'thumb' => PhotoSize::class,
  ];


}