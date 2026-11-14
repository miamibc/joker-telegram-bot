<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a button in a RichMessage. Exactly one of the fields other than text and style must be used to specify the type of the button.
 * @see https://core.telegram.org/bots/api#richmessagebutton
 *
 * @method RichText text() Text of the button. May contain only plain text, RichTextCustomEmoji and RichTextDateTime entities.
 * @method string style() Optional. Style of the button. Must be one of “danger”, “success”, “primary”, or “link” (the button is shown as a regular link without borders). Apps may use theme-specific colors for the button background and text based on the style. The style “link” is allowed only for callback buttons.
 * @method string url() Optional. HTTP or tg:// URL to be opened when the button is pressed. Links tg://user?id=<user_id> can be used to mention a user by their identifier without using a username, if this is allowed by their privacy settings.
 * @method string callback_data() Optional. Data to be sent in a callback query to the bot when the button is pressed, 1-64 bytes
 * @method WebAppInfo web_app() Optional. Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery. Available only in private chats between a user and the bot. Not supported for messages sent on behalf of a business account.
 * @method LoginUrl login_url() Optional. An HTTPS URL used to automatically authorize the user. Can be used as a replacement for the Telegram Login Widget. Not supported for ephemeral messages.
 * @method string switch_inline_query() Optional. If set, pressing the button will prompt the user to select one of their chats, open that chat and insert the bot's username and the specified inline query in the input field. May be empty, in which case just the bot's username will be inserted. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
 * @method string switch_inline_query_current_chat() Optional. If set, pressing the button will insert the bot's username and the specified inline query in the current chat's input field. May be empty, in which case only the bot's username will be inserted. Not supported in channels and for messages sent in channel direct messages chats and on behalf of a business account.
 * @method SwitchInlineQueryChosenChat switch_inline_query_chosen_chat() Optional. If set, pressing the button will prompt the user to select one of their chats of the specified type, open that chat and insert the bot's username and the specified inline query in the input field. Not supported for messages sent in channel direct messages chats and on behalf of a business account.
 * @method CopyTextButton copy_text() Optional. A button that copies the specified text to the clipboard
 * @method DisabledButton disabled() Optional. If set, then the button is disabled and does nothing
 */
class RichMessageButton extends Base
{

  protected $wrapper = [
    'text' => RichText::class,
    'web_app' => WebAppInfo::class,
    'login_url' => LoginUrl::class,
    'switch_inline_query_chosen_chat' => SwitchInlineQueryChosenChat::class,
    'copy_text' => CopyTextButton::class,
    'disabled' => DisabledButton::class,
  ];

}
