<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a voice note.
 * @see https://core.telegram.org/bots/api#voice
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer duration() Duration of the audio in seconds as defined by sender
 * @method string mime_type() Optional. MIME type of the file as defined by sender
 * @method integer file_size() Optional. File size
 */
class Voice extends Base
{

  protected $wrapper = [
  ];


}