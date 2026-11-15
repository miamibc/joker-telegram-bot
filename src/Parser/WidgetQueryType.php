<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the type of a query from a widget. Currently, it can be one of
 * Currently can be one of WidgetQueryTypeData, WidgetQueryTypeSignData, WidgetQueryTypeSentTransaction — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#widgetquerytype
 *
 * @method string type() Type of the query, always “data”
 * @method TonWalletCard wallet_card() WidgetQueryTypeData only. Optional. TON wallet card of the user who sent the query, if it was sent to the bot along with the query
 * @method WidgetQueryWallet wallet() WidgetQueryTypeSignData, WidgetQueryTypeSentTransaction only. The wallet that signed the data
 * @method string sign_data() WidgetQueryTypeSignData only. JSON-serialized signed data
 * @method string msg_hash() WidgetQueryTypeSentTransaction only. Value of msg_hash of the transaction
 */
class WidgetQueryType extends Base
{

  protected $wrapper = [
    'wallet_card' => TonWalletCard::class,
    'wallet' => WidgetQueryWallet::class,
  ];

}
