<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the source of a transaction, or its recipient for outgoing transactions. Currently, it can be one of TransactionPartnerUser, TransactionPartnerChat, TransactionPartnerAffiliateProgram, TransactionPartnerFragment, TransactionPartnerTelegramAds, TransactionPartnerTelegramApi, TransactionPartnerOther — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#transactionpartner
 *
 * @method string type() Type of the transaction partner: “user”, “chat”, “affiliate_program”, “fragment”, “telegram_ads”, “telegram_api” or “other”
 * @method string transaction_type() User only. Type of the transaction: “invoice_payment”, “paid_media_payment”, “gift_purchase”, “premium_purchase” or “business_account_transfer”
 * @method User user() User only. Information about the user
 * @method AffiliateInfo affiliate() User only. Optional. Information about the affiliate that received a commission via this transaction
 * @method string invoice_payload() User only. Optional. Bot-specified invoice payload
 * @method integer subscription_period() User only. Optional. The duration of the paid subscription
 * @method array paid_media() User only. Optional. Information about the paid media bought by the user
 * @method string paid_media_payload() User only. Optional. Bot-specified paid media payload
 * @method array gift() User and chat only. Optional. The gift sent by the bot
 * @method integer premium_subscription_duration() User only. Optional. Number of months the gifted Telegram Premium subscription will be active for
 * @method Chat chat() Chat only. Information about the chat
 * @method User sponsor_user() Affiliate program only. Optional. Information about the bot that sponsored the affiliate program
 * @method integer commission_per_mille() Affiliate program only. The number of Telegram Stars received by the bot for each 1000 Telegram Stars received by the affiliate program sponsor from referred users
 * @method RevenueWithdrawalState withdrawal_state() Fragment only. Optional. State of the transaction if the transaction is outgoing
 * @method integer request_count() Telegram API only. The number of successful requests that exceeded regular limits and were therefore billed
 */
class TransactionPartner extends Base
{

  protected $wrapper = [
    'user' => User::class,
    'affiliate' => AffiliateInfo::class,
    'chat' => Chat::class,
    'sponsor_user' => User::class,
    'withdrawal_state' => RevenueWithdrawalState::class,
  ];

}
