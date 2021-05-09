<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a general file (as opposed to photos, voice messages and audio files).
 * @see https://core.telegram.org/bots/api#document
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method PhotoSize thumb() Optional. Document thumbnail as defined by sender
 * @method string file_name() Optional. Original filename as defined by sender
 * @method string mime_type() Optional. MIME type of the file as defined by sender
 * @method integer file_size() Optional. File size
 */
class Document extends Base
{

  protected $wrapper = [
    'thumb' => PhotoSize::class,
  ];


}