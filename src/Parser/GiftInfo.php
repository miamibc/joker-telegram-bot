<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a regular gift that was sent or received.
 * @see https://core.telegram.org/bots/api#giftinfo
 *
 * @method Gift gift() Information about the gift
 * @method string owned_gift_id() Optional. Unique identifier of the received gift for the bot; only present for gifts received on behalf of business accounts
 * @method integer convert_star_count() Optional. Number of Telegram Stars that can be claimed by the receiver by converting the gift; omitted if conversion to Telegram Stars is impossible
 * @method integer prepaid_upgrade_star_count() Optional. Number of Telegram Stars that were prepaid for the ability to upgrade the gift
 * @method bool is_upgrade_separate() Optional. True, if the gift's upgrade was purchased after the gift was sent
 * @method bool can_be_upgraded() Optional. True, if the gift can be upgraded to a unique gift
 * @method string text() Optional. Text of the message that was added to the gift
 * @method MessageEntity[] entities() Optional. Special entities that appear in the text
 * @method bool is_private() Optional. True, if the sender and gift text are shown only to the gift receiver; otherwise, everyone will be able to see them
 * @method integer unique_gift_number() Optional. Unique number reserved for this gift when upgraded. See the number field in UniqueGift.
 */
class GiftInfo extends Base
{

  protected $wrapper = [
    'gift' => Gift::class,
    'entities' => MessageEntity::class,
  ];

}
