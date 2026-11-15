<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes a gift received and owned by a user or a chat. Currently, it can be one of
 * Currently can be one of OwnedGiftRegular, OwnedGiftUnique — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#ownedgift
 *
 * @method string type() Type of the gift, always “regular”
 * @method Gift gift() Information about the regular gift
 * @method string owned_gift_id() Optional. Unique identifier of the gift for the bot; for gifts received on behalf of business accounts only
 * @method User sender_user() Optional. Sender of the gift if it is a known user
 * @method integer send_date() Date the gift was sent in Unix time
 * @method string text() OwnedGiftRegular only. Optional. Text of the message that was added to the gift
 * @method MessageEntity[] entities() OwnedGiftRegular only. Optional. Special entities that appear in the text
 * @method bool is_private() OwnedGiftRegular only. Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method bool is_saved() Optional. True, if the gift is displayed on the account's profile page; for gifts received on behalf of business accounts only
 * @method bool can_be_upgraded() OwnedGiftRegular only. Optional. True, if the gift can be upgraded to a unique gift; for gifts received on behalf of business accounts only
 * @method bool was_refunded() OwnedGiftRegular only. Optional. True, if the gift was refunded and isn't available anymore
 * @method integer convert_star_count() OwnedGiftRegular only. Optional. Number of Telegram Stars that can be claimed by the receiver instead of the gift; omitted if the gift cannot be converted to Telegram Stars; for gifts received on behalf of business accounts only
 * @method integer prepaid_upgrade_star_count() OwnedGiftRegular only. Optional. Number of Telegram Stars that were paid for the ability to upgrade the gift
 * @method bool is_upgrade_separate() OwnedGiftRegular only. Optional. True, if the gift's upgrade was purchased after the gift was sent; for gifts received on behalf of business accounts only
 * @method integer unique_gift_number() OwnedGiftRegular only. Optional. Unique number reserved for this gift when upgraded. See the number field in UniqueGift.
 * @method bool can_be_transferred() OwnedGiftUnique only. Optional. True, if the gift can be transferred to another owner; for gifts received on behalf of business accounts only
 * @method integer transfer_star_count() OwnedGiftUnique only. Optional. Number of Telegram Stars that must be paid to transfer the gift; omitted if the bot cannot transfer the gift
 * @method integer next_transfer_date() OwnedGiftUnique only. Optional. Point in time (Unix timestamp) when the gift can be transferred. If it is in the past, then the gift can be transferred now.
 */
class OwnedGift extends Base
{

  protected $wrapper = [
    'gift' => Gift::class,
    'sender_user' => User::class,
    'entities' => MessageEntity::class,
  ];

}
