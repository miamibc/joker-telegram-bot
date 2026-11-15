<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about the quoted part of a message that is replied to by the given message.
 * @see https://core.telegram.org/bots/api#textquote
 *
 * @method string text() Text of the quoted part of a message that is replied to by the given message; may be empty if the quote was chosen from a rich message
 * @method MessageEntity[] entities() Optional. Special entities that appear in the quote. Currently, only bold, italic, underline, strikethrough, spoiler, custom_emoji, and date_time entities are kept in quotes.
 * @method RichText[] rich_texts() Optional. Rich text of the quote if it was chosen from a rich message. May contain only plain text, RichTextBold, RichTextItalic, RichTextUnderline, RichTextStrikethrough, RichTextSpoiler, RichTextSubscript, RichTextSuperscript, RichTextDateTime, RichTextCustomEmoji, RichTextMathematicalExpression, and arrays of them.
 * @method integer position() Approximate quote position in the original message in UTF-16 code units as specified by the sender. For quotes from rich messages, it is the position in the text formed by joining plain texts of all quotable texts of the message, separated by line feeds.
 * @method bool is_manual() Optional. True, if the quote was chosen manually by the message sender. Otherwise, the quote was added automatically by the server.
 */
class TextQuote extends Base
{

  protected $wrapper = [
    'entities' => MessageEntity::class,
    'rich_texts' => RichText::class,
  ];

}
