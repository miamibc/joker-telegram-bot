<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes an emoji. Currently, it can be one of
 * Currently can be one of EmojiTypeEmoji, EmojiTypeCustomEmoji — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#emojitype
 *
 * @method string type() Type of the emoji, always “emoji”
 * @method string emoji() EmojiTypeEmoji only. The emoji
 * @method string custom_emoji_id() EmojiTypeCustomEmoji only. Identifier of the custom emoji. Use getCustomEmojiStickers to get full information about the sticker.
 */
class EmojiType extends Base
{

  protected $wrapper = [
  ];

}
