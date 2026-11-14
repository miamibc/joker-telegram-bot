<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes paid media. Currently, it can be one of
 * Currently can be one of PaidMediaLivePhoto, PaidMediaPhoto, PaidMediaPreview, PaidMediaVideo — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#paidmedia
 *
 * @method string type() Type of the paid media, always “live_photo”
 * @method LivePhoto live_photo() PaidMediaLivePhoto only. The photo
 * @method PhotoSize[] photo() PaidMediaPhoto only. The photo
 * @method integer width() PaidMediaPreview only. Optional. Media width as defined by the sender
 * @method integer height() PaidMediaPreview only. Optional. Media height as defined by the sender
 * @method integer duration() PaidMediaPreview only. Optional. Duration of the media in seconds as defined by the sender
 * @method Video video() PaidMediaVideo only. The video
 */
class PaidMedia extends Base
{

  protected $wrapper = [
    'live_photo' => LivePhoto::class,
    'photo' => PhotoSize::class,
    'video' => Video::class,
  ];

}
