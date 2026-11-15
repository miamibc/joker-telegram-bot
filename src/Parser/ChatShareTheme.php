<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the theme of the screen for sharing a link to a user or a chat. Currently, it can be one of
 * Currently can be one of ChatShareThemeEmoji, ChatShareThemeUniqueGift — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#chatsharetheme
 *
 * @method string type() Type of the theme, always “emoji”
 * @method EmojiType[] emojis() ChatShareThemeEmoji only. The emoji; 0-6 regular or custom emoji
 * @method integer background_backdrop_id() ChatShareThemeEmoji only. Optional. Identifier of the backdrop used as the background; omitted if the default background is used. Use getChatShareThemeBackdrops to get details about the backdrop.
 * @method UniqueGift gift() ChatShareThemeUniqueGift only. The gift
 */
class ChatShareTheme extends Base
{

  protected $wrapper = [
    'emojis' => EmojiType::class,
    'gift' => UniqueGift::class,
  ];

}
