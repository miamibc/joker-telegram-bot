<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about a paid media purchase.
 * @see https://core.telegram.org/bots/api#paidmediapurchased
 *
 * @method User from() User who purchased the media
 * @method string paid_media_payload() Bot-specified paid media payload
 */
class PaidMediaPurchased extends Base
{

  protected $wrapper = [
    'from' => User::class,
  ];

}
