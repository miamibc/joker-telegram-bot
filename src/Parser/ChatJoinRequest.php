<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents a join request sent to a chat.
 * @see https://core.telegram.org/bots/api#chatjoinrequest
 *
 * @method Chat chat() Chat to which the request was sent
 * @method User from() User that sent the join request
 * @method integer user_chat_id() Identifier of a private chat with the user who sent the join request. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier. The bot can use this identifier for 5 minutes to send messages until the join request is processed, assuming no other administrator contacted the user.
 * @method integer date() Date the request was sent in Unix time
 * @method string bio() Optional. Bio of the user
 * @method ChatInviteLink invite_link() Optional. Chat invite link that was used by the user to send the join request
 * @method string query_id() Optional. Identifier of the join request query; for bots assigned to process join requests only. If present, then the bot must call sendChatJoinRequestWebApp or directly call answerChatJoinRequestQuery within 10 seconds.
 */
class ChatJoinRequest extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
    'from' => User::class,
    'invite_link' => ChatInviteLink::class,
  ];

}
