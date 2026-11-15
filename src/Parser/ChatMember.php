<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about one member of a chat. Currently, the following 6 types of chat members are supported:
 * Currently can be one of ChatMemberOwner, ChatMemberAdministrator, ChatMemberMember, ChatMemberRestricted, ChatMemberLeft, ChatMemberBanned — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#chatmember
 *
 * @method string status() The member's status in the chat, always “creator”
 * @method User user() Information about the user
 * @method bool is_anonymous() ChatMemberOwner, ChatMemberAdministrator only. True, if the user's presence in the chat is hidden
 * @method string custom_title() ChatMemberOwner, ChatMemberAdministrator only. Optional. Custom title for this user
 * @method bool can_be_edited() ChatMemberAdministrator only. True, if the bot is allowed to edit administrator privileges of that user
 * @method bool can_manage_chat() ChatMemberAdministrator only. True, if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
 * @method bool can_delete_messages() ChatMemberAdministrator only. True, if the administrator can delete messages of other users
 * @method bool can_manage_video_chats() ChatMemberAdministrator only. True, if the administrator can manage video chats
 * @method bool can_restrict_members() ChatMemberAdministrator only. True, if the administrator can restrict, ban or unban chat members, or access supergroup statistics
 * @method bool can_promote_members() ChatMemberAdministrator only. True, if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by the user)
 * @method bool can_change_info() ChatMemberAdministrator, ChatMemberRestricted only. True, if the user is allowed to change the chat title, photo and other settings
 * @method bool can_invite_users() ChatMemberAdministrator, ChatMemberRestricted only. True, if the user is allowed to invite new users to the chat
 * @method bool can_post_stories() ChatMemberAdministrator only. True, if the administrator can post stories to the chat
 * @method bool can_edit_stories() ChatMemberAdministrator only. True, if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
 * @method bool can_delete_stories() ChatMemberAdministrator only. True, if the administrator can delete stories posted by other users
 * @method bool can_post_messages() ChatMemberAdministrator only. Optional. True, if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
 * @method bool can_edit_messages() ChatMemberAdministrator only. Optional. True, if the administrator can edit messages of other users and can pin messages; for channels only
 * @method bool can_pin_messages() ChatMemberAdministrator, ChatMemberRestricted only. Optional. True, if the user is allowed to pin messages; for groups and supergroups only
 * @method bool can_manage_topics() ChatMemberAdministrator, ChatMemberRestricted only. Optional. True, if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
 * @method bool can_manage_direct_messages() ChatMemberAdministrator only. Optional. True, if the administrator can manage direct messages of the channel and decline suggested posts; for channels only
 * @method bool can_manage_tags() ChatMemberAdministrator only. Optional. True, if the administrator can edit the tags of regular members; for groups and supergroups only
 * @method bool can_send_welcome_messages() ChatMemberAdministrator only. True, if the administrator can manage chat welcome messages or directly send them in the case of bots
 * @method string tag() ChatMemberMember, ChatMemberRestricted only. Optional. Tag of the member
 * @method integer until_date() ChatMemberMember, ChatMemberRestricted, ChatMemberBanned only. Optional. Date when the user's subscription will expire; Unix time
 * @method bool is_member() ChatMemberRestricted only. True, if the user is a member of the chat at the moment of the request
 * @method bool can_send_messages() ChatMemberRestricted only. True, if the user is allowed to send text messages, rich messages, contacts, giveaways, giveaway winners, invoices, locations and venues
 * @method bool can_send_audios() ChatMemberRestricted only. True, if the user is allowed to send audios
 * @method bool can_send_documents() ChatMemberRestricted only. True, if the user is allowed to send documents
 * @method bool can_send_photos() ChatMemberRestricted only. True, if the user is allowed to send photos
 * @method bool can_send_videos() ChatMemberRestricted only. True, if the user is allowed to send videos
 * @method bool can_send_video_notes() ChatMemberRestricted only. True, if the user is allowed to send video notes
 * @method bool can_send_voice_notes() ChatMemberRestricted only. True, if the user is allowed to send voice notes
 * @method bool can_send_polls() ChatMemberRestricted only. True, if the user is allowed to send polls and checklists
 * @method bool can_send_other_messages() ChatMemberRestricted only. True, if the user is allowed to send animations, games, stickers, widgets and use inline bots
 * @method bool can_add_web_page_previews() ChatMemberRestricted only. True, if the user is allowed to add web page previews to their messages
 * @method bool can_react_to_messages() ChatMemberRestricted only. True, if the user is allowed to react to messages
 * @method bool can_edit_tag() ChatMemberRestricted only. True, if the user is allowed to edit their own tag
 */
class ChatMember extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];

}
