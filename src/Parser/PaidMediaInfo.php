<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the paid media added to a message.
 * @see https://core.telegram.org/bots/api#paidmediainfo
 *
 * @method integer star_count() The number of Telegram Stars that must be paid to buy access to the media
 * @method PaidMedia[] paid_media() Information about the paid media
 */
class PaidMediaInfo extends Base
{

  protected $wrapper = [
    'paid_media' => PaidMedia::class,
  ];

}
