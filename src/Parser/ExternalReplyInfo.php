<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about a message that is being replied to, which may come from another chat or forum topic.
 * @see https://core.telegram.org/bots/api#externalreplyinfo
 *
 * @method MessageOrigin origin() Origin of the message replied to by the given message
 * @method Chat chat() Optional. Chat the original message belongs to. Available only if the chat is a supergroup or a channel.
 * @method integer message_id() Optional. Unique message identifier inside the original chat. Available only if the original chat is a supergroup or a channel.
 * @method LinkPreviewOptions link_preview_options() Optional. Options used for link preview generation for the original message, if it is a text message
 * @method Animation animation() Optional. Message is an animation, information about the animation
 * @method Audio audio() Optional. Message is an audio file, information about the file
 * @method Document document() Optional. Message is a general file, information about the file
 * @method LivePhoto live_photo() Optional. Message is a live photo, information about the live photo
 * @method PaidMediaInfo paid_media() Optional. Message contains paid media; information about the paid media
 * @method PhotoSize[] photo() Optional. Message is a photo, available sizes of the photo
 * @method Sticker sticker() Optional. Message is a sticker, information about the sticker
 * @method Story story() Optional. Message is a forwarded story
 * @method Video video() Optional. Message is a video, information about the video
 * @method VideoNote video_note() Optional. Message is a video note, information about the video message
 * @method Voice voice() Optional. Message is a voice message, information about the file
 * @method Widget widget() Optional. Message is an HTML widget, information about the widget
 * @method bool has_media_spoiler() Optional. True, if the message media is covered by a spoiler animation
 * @method Checklist checklist() Optional. Message is a checklist
 * @method Contact contact() Optional. Message is a shared contact, information about the contact
 * @method Dice dice() Optional. Message is a dice with random value
 * @method Game game() Optional. Message is a game, information about the game. More about games »
 * @method Giveaway giveaway() Optional. Message is a scheduled giveaway, information about the giveaway
 * @method GiveawayWinners giveaway_winners() Optional. A giveaway with public winners was completed
 * @method Invoice invoice() Optional. Message is an invoice for a payment, information about the invoice. More about payments »
 * @method Location location() Optional. Message is a shared location, information about the location
 * @method Poll poll() Optional. Message is a native poll, information about the poll
 * @method Venue venue() Optional. Message is a venue, information about the venue
 */
class ExternalReplyInfo extends Base
{

  protected $wrapper = [
    'origin' => MessageOrigin::class,
    'chat' => Chat::class,
    'link_preview_options' => LinkPreviewOptions::class,
    'animation' => Animation::class,
    'audio' => Audio::class,
    'document' => Document::class,
    'live_photo' => LivePhoto::class,
    'paid_media' => PaidMediaInfo::class,
    'photo' => PhotoSize::class,
    'sticker' => Sticker::class,
    'story' => Story::class,
    'video' => Video::class,
    'video_note' => VideoNote::class,
    'voice' => Voice::class,
    'widget' => Widget::class,
    'checklist' => Checklist::class,
    'contact' => Contact::class,
    'dice' => Dice::class,
    'game' => Game::class,
    'giveaway' => Giveaway::class,
    'giveaway_winners' => GiveawayWinners::class,
    'invoice' => Invoice::class,
    'location' => Location::class,
    'poll' => Poll::class,
    'venue' => Venue::class,
  ];

}
