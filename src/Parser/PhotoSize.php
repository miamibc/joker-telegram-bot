<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents one size of a photo or a file / sticker thumbnail.
 * @see https://core.telegram.org/bots/api#poll
 *
 * @method string file_id Identifier for this file, which can be used to download or reuse the file
 * @method string file_unique_id Unique identifier for this file, which is supposed to be the same over time and for different bots. Can't be used to download or reuse the file.
 * @method integer width() Photo width
 * @method integer height() Photo height
 * @method integer file_size() Optional. File size
 */
class PhotoSize extends Base
{

  protected $wrapper = [
  ];


}