<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Contains the list of gifts received and owned by a user or a chat.
 * @see https://core.telegram.org/bots/api#ownedgifts
 *
 * @method integer total_count() The total number of gifts owned by the user or the chat
 * @method OwnedGift[] gifts() The list of gifts
 * @method string next_offset() Optional. Offset for the next request. If empty, then there are no more results.
 */
class OwnedGifts extends Base
{

  protected $wrapper = [
    'gifts' => OwnedGift::class,
  ];

}
