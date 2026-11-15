<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about the failed approval of a suggested post. Currently, only caused by insufficient user funds at the time of approval.
 * @see https://core.telegram.org/bots/api#suggestedpostapprovalfailed
 *
 * @method Message suggested_post_message() Optional. Message containing the suggested post whose approval has failed. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method SuggestedPostPrice price() Expected price of the post
 */
class SuggestedPostApprovalFailed extends Base
{

  protected $wrapper = [
    'suggested_post_message' => Message::class,
    'price' => SuggestedPostPrice::class,
  ];

}
