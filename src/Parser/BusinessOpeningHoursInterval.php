<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes an interval of time during which a business is open.
 * @see https://core.telegram.org/bots/api#businessopeninghoursinterval
 *
 * @method integer opening_minute() The minute's sequence number in a week, starting on Monday, marking the start of the time interval during which the business is open; 0 - 7 * 24 * 60
 * @method integer closing_minute() The minute's sequence number in a week, starting on Monday, marking the end of the time interval during which the business is open; 0 - 8 * 24 * 60
 */
class BusinessOpeningHoursInterval extends Base
{

  protected $wrapper = [
  ];

}
