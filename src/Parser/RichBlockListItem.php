<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * An item of a list.
 * @see https://core.telegram.org/bots/api#richblocklistitem
 *
 * @method string label() Label of the item
 * @method RichBlock[] blocks() The content of the item
 * @method bool has_checkbox() Optional. True, if the item has a checkbox
 * @method bool is_checked() Optional. True, if the item has a checked checkbox
 * @method integer value() Optional. For ordered lists, the numeric value of the item label
 * @method string type() Optional. For ordered lists, the type of the item label; must be one of “a” for lowercase letters, “A” for uppercase letters, “i” for lowercase Roman numerals, “I” for uppercase Roman numerals, or “1” for decimal numbers
 */
class RichBlockListItem extends Base
{

  protected $wrapper = [
    'blocks' => RichBlock::class,
  ];

}
