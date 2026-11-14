<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a unique gift that was sent or received.
 * @see https://core.telegram.org/bots/api#uniquegiftinfo
 *
 * @method UniqueGift gift() Information about the gift
 * @method string origin() Origin of the gift. Currently, either “upgrade” for gifts upgraded from regular gifts, “transfer” for gifts transferred from other users or channels, “resale” for gifts bought from other users, “gifted_upgrade” for upgrades purchased after the gift was sent, or “offer” for gifts bought or sold through gift purchase offers.
 * @method string text() Optional. Text of the message that was added to the gift
 * @method MessageEntity[] entities() Optional. Special entities that appear in the text
 * @method bool is_private() Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method string last_resale_currency() Optional. For gifts bought from other users, the currency in which the payment for the gift was done. Currently, one of “XTR” for Telegram Stars or “TON” for TON grams.
 * @method integer last_resale_amount() Optional. For gifts bought from other users, the price paid for the gift in either Telegram Stars or nanograms
 * @method string owned_gift_id() Optional. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method integer transfer_star_count() Optional. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method integer next_transfer_date() Optional. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
 */
class UniqueGiftInfo extends Base
{

  protected $wrapper = [
    'gift' => UniqueGift::class,
    'entities' => MessageEntity::class,
  ];

}
