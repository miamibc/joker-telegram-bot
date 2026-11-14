<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Caption of a rich formatted block.
 * @see https://core.telegram.org/bots/api#richblockcaption
 *
 * @method RichText text() Block caption
 * @method RichText credit() Optional. Block credit which corresponds to the HTML tag <cite>
 */
class RichBlockCaption extends Base
{

  protected $wrapper = [
    'text' => RichText::class,
    'credit' => RichText::class,
  ];

}
