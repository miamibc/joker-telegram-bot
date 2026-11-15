<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Contains information about a suggested post.
 * @see https://core.telegram.org/bots/api#suggestedpostinfo
 *
 * @method string state() State of the suggested post. Currently, it can be one of “pending”, “approved”, “declined”.
 * @method SuggestedPostPrice price() Optional. Proposed price of the post. If the field is omitted, then the post is unpaid.
 * @method integer send_date() Optional. Proposed send date of the post. If the field is omitted, then the post can be published at any time within 30 days at the sole discretion of the user or administrator who approves it.
 */
class SuggestedPostInfo extends Base
{

  protected $wrapper = [
    'price' => SuggestedPostPrice::class,
  ];

}
