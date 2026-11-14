<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the source of a transaction, or its recipient for outgoing transactions. Currently, it can be one of
 * Currently can be one of TransactionPartnerUser, TransactionPartnerChat, TransactionPartnerAffiliateProgram, TransactionPartnerFragment, TransactionPartnerTelegramAds, TransactionPartnerTelegramApi, TransactionPartnerOther — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#transactionpartner
 *
 * @method string type() Type of the transaction partner, always “user”
 * @method string transaction_type() TransactionPartnerUser only. Type of the transaction, currently one of “invoice_payment” for payments via invoices, “paid_media_payment” for payments for paid media, “gift_purchase” for gifts sent by the bot, “premium_purchase” for Telegram Premium subscriptions gifted by the bot, “business_account_transfer” for direct transfers from managed business accounts
 * @method User user() TransactionPartnerUser only. Information about the user
 * @method AffiliateInfo affiliate() TransactionPartnerUser only. Optional. Information about the affiliate that received a commission via this transaction. Can be available only for “invoice_payment” and “paid_media_payment” transactions.
 * @method string invoice_payload() TransactionPartnerUser only. Optional. Bot-specified invoice payload. Can be available only for “invoice_payment” transactions.
 * @method integer subscription_period() TransactionPartnerUser only. Optional. The duration of the paid subscription. Can be available only for “invoice_payment” transactions.
 * @method PaidMedia[] paid_media() TransactionPartnerUser only. Optional. Information about the paid media bought by the user; for “paid_media_payment” transactions only
 * @method string paid_media_payload() TransactionPartnerUser only. Optional. Bot-specified paid media payload. Can be available only for “paid_media_payment” transactions.
 * @method Gift gift() TransactionPartnerUser, TransactionPartnerChat only. Optional. The gift sent to the user by the bot; for “gift_purchase” transactions only
 * @method integer premium_subscription_duration() TransactionPartnerUser only. Optional. Number of months the gifted Telegram Premium subscription will be active for; for “premium_purchase” transactions only
 * @method Chat chat() TransactionPartnerChat only. Information about the chat
 * @method User sponsor_user() TransactionPartnerAffiliateProgram only. Optional. Information about the bot that sponsored the affiliate program
 * @method integer commission_per_mille() TransactionPartnerAffiliateProgram only. The number of Telegram Stars received by the bot for each 1000 Telegram Stars received by the affiliate program sponsor from referred users
 * @method RevenueWithdrawalState withdrawal_state() TransactionPartnerFragment only. Optional. State of the transaction if the transaction is outgoing
 * @method integer request_count() TransactionPartnerTelegramApi only. The number of successful requests that exceeded regular limits and were therefore billed
 */
class TransactionPartner extends Base
{

  protected $wrapper = [
    'user' => User::class,
    'affiliate' => AffiliateInfo::class,
    'paid_media' => PaidMedia::class,
    'gift' => Gift::class,
    'chat' => Chat::class,
    'sponsor_user' => User::class,
    'withdrawal_state' => RevenueWithdrawalState::class,
  ];

}
