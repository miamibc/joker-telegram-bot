<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the state of a revenue withdrawal operation. Currently, it can be one of
 * Currently can be one of RevenueWithdrawalStatePending, RevenueWithdrawalStateSucceeded, RevenueWithdrawalStateFailed — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#revenuewithdrawalstate
 *
 * @method string type() Type of the state, always “pending”
 * @method integer date() RevenueWithdrawalStateSucceeded only. Date the withdrawal was completed in Unix time
 * @method string url() RevenueWithdrawalStateSucceeded only. An HTTPS URL that can be used to see transaction details
 */
class RevenueWithdrawalState extends Base
{

  protected $wrapper = [
  ];

}
