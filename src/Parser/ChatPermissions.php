<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes actions that a non-administrator user is allowed to take in a chat.
 * @see https://core.telegram.org/bots/api#chatpermissions
 *
 * @method bool can_send_messages() Optional. True, if the user is allowed to send text messages, rich messages, contacts, giveaways, giveaway winners, invoices, locations and venues
 * @method bool can_send_audios() Optional. True, if the user is allowed to send audios
 * @method bool can_send_documents() Optional. True, if the user is allowed to send documents
 * @method bool can_send_photos() Optional. True, if the user is allowed to send photos
 * @method bool can_send_videos() Optional. True, if the user is allowed to send videos
 * @method bool can_send_video_notes() Optional. True, if the user is allowed to send video notes
 * @method bool can_send_voice_notes() Optional. True, if the user is allowed to send voice notes
 * @method bool can_send_polls() Optional. True, if the user is allowed to send polls and checklists
 * @method bool can_send_other_messages() Optional. True, if the user is allowed to send animations, games, stickers, widgets and use inline bots
 * @method bool can_add_web_page_previews() Optional. True, if the user is allowed to add web page previews to their messages
 * @method bool can_react_to_messages() Optional. True, if the user is allowed to react to messages. If omitted, defaults to the value of can_send_messages.
 * @method bool can_edit_tag() Optional. True, if the user is allowed to edit their own tag. If omitted, defaults to the value of can_pin_messages.
 * @method bool can_change_info() Optional. True, if the user is allowed to change the chat title, photo and other settings. Ignored in public supergroups.
 * @method bool can_invite_users() Optional. True, if the user is allowed to invite new users to the chat
 * @method bool can_pin_messages() Optional. True, if the user is allowed to pin messages. Ignored in public supergroups.
 * @method bool can_manage_topics() Optional. True, if the user is allowed to create forum topics. If omitted, defaults to the value of can_pin_messages.
 */
class ChatPermissions extends Base
{

  protected $wrapper = [
  ];

}
