<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents a result of an inline query that was chosen by the user and sent to their chat partner.
 * @see https://core.telegram.org/bots/api#choseninlineresult
 *
 * @method string result_id() The unique identifier for the result that was chosen
 * @method User from() The user that chose the result
 * @method Location location() Optional. Sender location, only for bots that require user location
 * @method string inline_message_id() Optional. Identifier of the sent inline message. Available only if there is an inline keyboard attached to the message. Will be also received in callback queries and can be used to edit the message.
 * @method string query() The query that was used to obtain the result
 */
class ChosenInlineResult extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'location' => Location::class,
  ];

}
