<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an inline keyboard button that copies specified text to the clipboard.
 * @see https://core.telegram.org/bots/api#copytextbutton
 *
 * @method string text() The text to be copied to the clipboard; 1-256 characters
 */
class CopyTextButton extends Base
{

  protected $wrapper = [
  ];

}
