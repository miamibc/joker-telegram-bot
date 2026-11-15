<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a gift that can be sent by the bot.
 * @see https://core.telegram.org/bots/api#gift
 *
 * @method string id() Unique identifier of the gift
 * @method Sticker sticker() The sticker that represents the gift
 * @method integer star_count() The number of Telegram Stars that must be paid to send the sticker
 * @method integer upgrade_star_count() Optional. The number of Telegram Stars that must be paid to upgrade the gift to a unique one
 * @method bool is_premium() Optional. True, if the gift can only be purchased by Telegram Premium subscribers
 * @method bool has_colors() Optional. True, if the gift can be used (after being upgraded) to customize a user's appearance
 * @method integer total_count() Optional. The total number of gifts of this type that can be sent by all users; for limited gifts only
 * @method integer remaining_count() Optional. The number of remaining gifts of this type that can be sent by all users; for limited gifts only
 * @method integer personal_total_count() Optional. The total number of gifts of this type that can be sent by the bot; for limited gifts only
 * @method integer personal_remaining_count() Optional. The number of remaining gifts of this type that can be sent by the bot; for limited gifts only
 * @method GiftBackground background() Optional. Background of the gift
 * @method integer unique_gift_variant_count() Optional. The total number of different unique gifts that can be obtained by upgrading the gift
 * @method Chat publisher_chat() Optional. Information about the chat that published the gift
 */
class Gift extends Base
{

  protected $wrapper = [
    'sticker' => Sticker::class,
    'background' => GiftBackground::class,
    'publisher_chat' => Chat::class,
  ];

}
