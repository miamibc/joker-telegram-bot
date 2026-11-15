<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a rich formatted text. Currently, it can be either a String for plain text, an Array of RichText, or any of the following types:
 * Currently can be one of RichTextBold, RichTextItalic, RichTextUnderline, RichTextStrikethrough, RichTextSpoiler, RichTextDateTime, RichTextTextMention, RichTextSubscript, RichTextSuperscript, RichTextMarked, RichTextCode, RichTextCustomEmoji, RichTextMathematicalExpression, RichTextUrl, RichTextEmailAddress, RichTextPhoneNumber, RichTextBankCardNumber, RichTextTonAddress, RichTextMention, RichTextHashtag, RichTextCashtag, RichTextBotCommand, RichTextButton, RichTextAnchor, RichTextAnchorLink, RichTextReference, RichTextReferenceLink — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#richtext
 *
 * @method string type() Type of the rich text, always “bold”
 * @method RichText text() RichTextBold, RichTextItalic, RichTextUnderline, RichTextStrikethrough, RichTextSpoiler, RichTextDateTime, RichTextTextMention, RichTextSubscript, RichTextSuperscript, RichTextMarked, RichTextCode, RichTextUrl, RichTextEmailAddress, RichTextPhoneNumber, RichTextBankCardNumber, RichTextTonAddress, RichTextMention, RichTextHashtag, RichTextCashtag, RichTextBotCommand, RichTextAnchorLink, RichTextReference, RichTextReferenceLink only. The text
 * @method integer unix_time() RichTextDateTime only. The Unix time associated with the entity
 * @method string date_time_format() RichTextDateTime only. The string that defines the formatting of the date and time. See date-time entity formatting for more details.
 * @method User user() RichTextTextMention only. The mentioned user
 * @method string custom_emoji_id() RichTextCustomEmoji only. Unique identifier of the custom emoji. Use getCustomEmojiStickers to get full information about the sticker.
 * @method string alternative_text() RichTextCustomEmoji only. Alternative emoji for the custom emoji
 * @method string expression() RichTextMathematicalExpression only. The expression in LaTeX format
 * @method string url() RichTextUrl only. URL of the link
 * @method string email_address() RichTextEmailAddress only. The email address
 * @method string phone_number() RichTextPhoneNumber only. The phone number
 * @method string bank_card_number() RichTextBankCardNumber only. The bank card number
 * @method string address() RichTextTonAddress only. The address
 * @method string username() RichTextMention only. The username
 * @method string hashtag() RichTextHashtag only. The hashtag
 * @method string cashtag() RichTextCashtag only. The cashtag
 * @method string bot_command() RichTextBotCommand only. The bot command
 * @method RichMessageButton button() RichTextButton only. The button
 * @method string name() RichTextAnchor, RichTextReference only. The name of the anchor
 * @method string anchor_name() RichTextAnchorLink only. The name of the anchor. If the name is empty, then the link brings back to the top of the message.
 * @method string reference_name() RichTextReferenceLink only. The name of the reference
 */
class RichText extends Base
{

  protected $wrapper = [
    'text' => RichText::class,
    'user' => User::class,
    'button' => RichMessageButton::class,
  ];

}
