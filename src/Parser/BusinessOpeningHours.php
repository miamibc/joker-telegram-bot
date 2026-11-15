<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the opening hours of a business.
 * @see https://core.telegram.org/bots/api#businessopeninghours
 *
 * @method string time_zone_name() Unique name of the time zone for which the opening hours are defined
 * @method BusinessOpeningHoursInterval[] opening_hours() List of time intervals describing business opening hours
 */
class BusinessOpeningHours extends Base
{

  protected $wrapper = [
    'opening_hours' => BusinessOpeningHoursInterval::class,
  ];

}
