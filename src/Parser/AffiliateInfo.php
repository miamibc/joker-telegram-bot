<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Contains information about the affiliate that received a commission via this transaction.
 * @see https://core.telegram.org/bots/api#affiliateinfo
 *
 * @method User affiliate_user() Optional. The bot or the user that received an affiliate commission if it was received by a bot or a user
 * @method Chat affiliate_chat() Optional. The chat that received an affiliate commission if it was received by a chat
 * @method integer commission_per_mille() The number of Telegram Stars received by the affiliate for each 1000 Telegram Stars received by the bot from referred users
 * @method integer amount() Integer amount of Telegram Stars received by the affiliate from the transaction, rounded to 0; can be negative for refunds
 * @method integer nanostar_amount() Optional. The number of 1/1000000000 shares of Telegram Stars received by the affiliate; from -999999999 to 999999999; can be negative for refunds
 */
class AffiliateInfo extends Base
{

  protected $wrapper = [
    'affiliate_user' => User::class,
    'affiliate_chat' => Chat::class,
  ];

}
