<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes a unique gift that was upgraded from a regular gift.
 * @see https://core.telegram.org/bots/api#uniquegift
 *
 * @method string gift_id() Identifier of the regular gift from which the gift was upgraded
 * @method string base_name() Human-readable name of the regular gift from which this unique gift was upgraded
 * @method string name() Unique name of the gift. This name can be used in https://t.me/nft/... links and story areas.
 * @method integer number() Unique number of the upgraded gift among gifts upgraded from the same regular gift
 * @method UniqueGiftModel model() Model of the gift
 * @method UniqueGiftSymbol symbol() Symbol of the gift
 * @method UniqueGiftBackdrop backdrop() Backdrop of the gift
 * @method bool is_premium() Optional. True, if the original regular gift was exclusively purchaseable by Telegram Premium subscribers
 * @method bool is_burned() Optional. True, if the gift was used to craft another gift and isn't available anymore
 * @method bool is_from_blockchain() Optional. True, if the gift is assigned from the TON blockchain and can't be resold or transferred in Telegram
 * @method UniqueGiftColors colors() Optional. The color scheme that can be used by the gift's owner for the chat's name, replies to messages and link previews; for business account gifts and gifts that are currently on sale only
 * @method Chat publisher_chat() Optional. Information about the chat that published the gift
 */
class UniqueGift extends Base
{

  protected $wrapper = [
    'model' => UniqueGiftModel::class,
    'symbol' => UniqueGiftSymbol::class,
    'backdrop' => UniqueGiftBackdrop::class,
    'colors' => UniqueGiftColors::class,
    'publisher_chat' => Chat::class,
  ];

}
