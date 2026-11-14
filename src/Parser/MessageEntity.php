<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents one special entity in a text message. For example, hashtags, usernames, URLs, etc.
 * @see https://core.telegram.org/bots/api#messageentity
 *
 * @method string type() Type of the entity. Currently, can be “mention” (@username), “hashtag” (#hashtag or #hashtag@chatusername), “cashtag” ($USD or $USD@chatusername), “bot_command” (/start@jobs_bot), “url” (https://telegram.org), “email” (do-not-reply@telegram.org), “phone_number” (+1-212-555-0123), “bank_card_number” (bank card number), “ton_address” (address in the TON blockchain), “bold” (bold text), “italic” (italic text), “underline” (underlined text), “strikethrough” (strikethrough text), “spoiler” (spoiler message), “blockquote” (block quotation), “expandable_blockquote” (collapsed-by-default block quotation), “code” (monowidth string), “pre” (monowidth block), “text_link” (for clickable text URLs), “text_mention” (for users without usernames), “custom_emoji” (for inline custom emoji stickers), or “date_time” (for formatted date and time).
 * @method integer offset() Offset in UTF-16 code units to the start of the entity
 * @method integer length() Length of the entity in UTF-16 code units
 * @method string url() Optional. For “text_link” only, URL that will be opened after user taps on the text
 * @method User user() Optional. For “text_mention” only, the mentioned user
 * @method string language() Optional. For “pre” only, the programming language of the entity text
 * @method string custom_emoji_id() Optional. For “custom_emoji” only, unique identifier of the custom emoji. Use getCustomEmojiStickers to get full information about the sticker.
 * @method integer unix_time() Optional. For “date_time” only, the Unix time associated with the entity
 * @method string date_time_format() Optional. For “date_time” only, the string that defines the formatting of the date and time. See date-time entity formatting for more details.
 */
class MessageEntity extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];


}