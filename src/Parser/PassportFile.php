<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a file uploaded to Telegram Passport. Currently all Telegram Passport files are in JPEG format when decrypted and don't exceed 10MB.
 * @see https://core.telegram.org/bots/api#passportfile
 *
 * @method string file_id() Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id() Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer file_size() File size in bytes
 * @method integer file_date() Unix time when the file was uploaded
 */
class PassportFile extends Base
{

  protected $wrapper = [
  ];

}
