<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents an invite link for a chat.
 * @see https://core.telegram.org/bots/api#chatinvitelink
 *
 * @method string invite_link() The invite link. If the link was created by another chat administrator, then the second part of the link will be replaced with “…”.
 * @method User creator() Creator of the link
 * @method bool creates_join_request() True, if users joining the chat via the link need to be approved by chat administrators
 * @method bool is_primary() True, if the link is primary
 * @method bool is_revoked() True, if the link is revoked
 * @method string name() Optional. Invite link name
 * @method integer expire_date() Optional. Point in time (Unix timestamp) when the link will expire or has been expired
 * @method integer member_limit() Optional. The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
 * @method integer pending_join_request_count() Optional. Number of pending join requests created using this link
 * @method integer subscription_period() Optional. The number of seconds the subscription will be active for before the next payment
 * @method integer subscription_price() Optional. The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat using the link
 */
class ChatInviteLink extends Base
{

  protected $wrapper = [
    'creator' => User::class,
  ];

}
