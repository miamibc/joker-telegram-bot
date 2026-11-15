<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about changes to a user payment subscription toward the current bot.
 * @see https://core.telegram.org/bots/api#botsubscriptionupdated
 *
 * @method User user() User who subscribed for payments toward the bot
 * @method string invoice_payload() Bot-specified invoice payload
 * @method string state() The new state of the subscription. Currently, it can be one of “canceled” if the user canceled the subscription, “active” if the user re-enabled a previously canceled subscription, or “failed” if payment for the subscription failed.
 */
class BotSubscriptionUpdated extends Base
{

  protected $wrapper = [
    'user' => User::class,
  ];

}
