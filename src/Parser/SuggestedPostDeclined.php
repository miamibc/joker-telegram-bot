<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about the rejection of a suggested post.
 * @see https://core.telegram.org/bots/api#suggestedpostdeclined
 *
 * @method Message suggested_post_message() Optional. Message containing the suggested post. Note that the Message object in this field will not contain the reply_to_message field even if it itself is a reply.
 * @method string comment() Optional. Comment with which the post was declined
 */
class SuggestedPostDeclined extends Base
{

  protected $wrapper = [
    'suggested_post_message' => Message::class,
  ];

}
