<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Rich formatted message.
 * @see https://core.telegram.org/bots/api#richmessage
 *
 * @method RichBlock[] blocks() Content of the message
 * @method bool is_rtl() Optional. True, if the rich message must be shown right-to-left
 */
class RichMessage extends Base
{

  protected $wrapper = [
    'blocks' => RichBlock::class,
  ];

}
