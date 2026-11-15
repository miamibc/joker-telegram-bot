<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a successful payment for a suggested post.
 * @see https://core.telegram.org/bots/api#suggestedpostpaid
 *
 * @method Message suggested_post_message() Optional. Message containing the suggested post. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method string currency() Currency in which the payment was made. Currently, one of “XTR” for Telegram Stars or “TON” for TON grams.
 * @method integer amount() Optional. The amount of the currency that was received by the channel in nanograms; for payments in TON grams only
 * @method StarAmount star_amount() Optional. The amount of Telegram Stars that was received by the channel; for payments in Telegram Stars only
 */
class SuggestedPostPaid extends Base
{

  protected $wrapper = [
    'suggested_post_message' => Message::class,
    'star_amount' => StarAmount::class,
  ];

}
