<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an answer of a user in a non-anonymous poll.
 * @see https://core.telegram.org/bots/api#pollanswer
 *
 * @method string poll_id() Unique poll identifier
 * @method Chat voter_chat() Optional. The chat that changed the answer to the poll, if the voter is anonymous
 * @method User user() Optional. The user that changed the answer to the poll, if the voter isn't anonymous
 * @method integer[] option_ids() 0-based identifiers of chosen answer options. May be empty if the vote was retracted.
 * @method string[] option_persistent_ids() Persistent identifiers of the chosen answer options. May be empty if the vote was retracted.
 */
class PollAnswer extends Base
{

  protected $wrapper = [
    'voter_chat' => Chat::class,
    'user' => User::class,
  ];


}