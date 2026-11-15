<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents changes in the status of a chat member.
 * @see https://core.telegram.org/bots/api#chatmemberupdated
 *
 * @method Chat chat() Chat the user belongs to
 * @method User from() Performer of the action, which resulted in the change
 * @method integer date() Date the change was done in Unix time
 * @method ChatMember old_chat_member() Previous information about the chat member
 * @method ChatMember new_chat_member() New information about the chat member
 * @method ChatInviteLink invite_link() Optional. Chat invite link, which was used by the user to join the chat; for joining by invite link events only
 * @method bool via_join_request() Optional. True, if the user joined the chat after sending a direct join request without using an invite link and being approved by an administrator
 * @method bool via_chat_folder_invite_link() Optional. True, if the user joined the chat via a chat folder invite link
 */
class ChatMemberUpdated extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
    'from' => User::class,
    'old_chat_member' => ChatMember::class,
    'new_chat_member' => ChatMember::class,
    'invite_link' => ChatInviteLink::class,
  ];

}
