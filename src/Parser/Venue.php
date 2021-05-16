<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a venue.
 * @see https://core.telegram.org/bots/api#venue
 *
 * @method Location location Venue location. Can't be a live location
 * @method string title 	Name of the venue
 * @method string address Address of the venue
 * @method string foursquare_id	Optional. Foursquare identifier of the venue
 * @method string foursquare_type 	Optional. Foursquare type of the venue. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
 * @method string google_place_id 	Optional. Google Places identifier of the venue
 * @method string google_place_type Optional. Google Places type of the venue. (See supported types.)
 */
class Venue extends Base
{

  protected $wrapper = [
    'location' => Location::class,
  ];


}