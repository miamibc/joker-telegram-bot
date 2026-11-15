<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * At most one of the optional fields can be present in any given object.
 * @see https://core.telegram.org/bots/api#pollmedia
 *
 * @method Animation animation() Optional. Media is an animation, information about the animation
 * @method Audio audio() Optional. Media is an audio file, information about the file; currently, can't be received in a poll option
 * @method Document document() Optional. Media is a general file, information about the file; currently, can't be received in a poll option
 * @method Link link() Optional. The HTTP link attached to the poll option
 * @method LivePhoto live_photo() Optional. Media is a live photo, information about the live photo
 * @method Location location() Optional. Media is a shared location, information about the location
 * @method PhotoSize[] photo() Optional. Media is a photo, available sizes of the photo
 * @method Sticker sticker() Optional. Media is a sticker, information about the sticker; currently, for poll options only
 * @method Venue venue() Optional. Media is a venue, information about the venue
 * @method Video video() Optional. Media is a video, information about the video
 */
class PollMedia extends Base
{

  protected $wrapper = [
    'animation' => Animation::class,
    'audio' => Audio::class,
    'document' => Document::class,
    'link' => Link::class,
    'live_photo' => LivePhoto::class,
    'location' => Location::class,
    'photo' => PhotoSize::class,
    'sticker' => Sticker::class,
    'venue' => Venue::class,
    'video' => Video::class,
  ];

}
