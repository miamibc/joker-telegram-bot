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
 * @method string type Type of the entity. Can be “mention” (@username), “hashtag” (#hashtag), “cashtag” ($USD), “bot_command” (/start@jobs_bot), “url” (https://telegram.org), “email” (do-not-reply@telegram.org), “phone_number” (+1-212-555-0123), “bold” (bold text), “italic” (italic text), “underline” (underlined text), “strikethrough” (strikethrough text), “code” (monowidth string), “pre” (monowidth block), “text_link” (for clickable text URLs), “text_mention” (for users without usernames)
 * @method integer offset Offset in UTF-16 code units to the start of the entity
 * @method integer length Length of the entity in UTF-16 code units
 * @method string url Optional. For “text_link” only, url that will be opened after user taps on the text
 * @method User user Optional. For “text_mention” only, the mentioned user
 * @method string language Optional. For “pre” only, the programming language of the entity text
 */
class MessageEntity extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];


}