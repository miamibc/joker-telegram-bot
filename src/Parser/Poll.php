<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains information about a poll.
 * @see https://core.telegram.org/bots/api#poll
 *
 * @method string id Unique poll identifier
 * @method string question Poll question, 1-300 characters
 * @method PollOption[] options List of poll options
 * @method integer total_voter_count Total number of users that voted in the poll
 * @method boolean is_closed True, if the poll is closed
 * @method boolean is_anonymous True, if the poll is anonymous
 * @method string type Poll type, currently can be “regular” or “quiz”
 * @method boolean allows_multiple_answers True, if the poll allows multiple answers
 * @method integer correct_option_id Optional. 0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.
 * @method string explanation Optional. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
 * @method MessageEntry[] explanation_entities Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
 * @method integer open_period  Optional. Amount of time in seconds the poll will be active after creation
 * @method integer close_period  Optional. Point in time (Unix timestamp) when the poll will be automatically closed
 */
class Poll extends Base
{

  protected $wrapper = [
  ];


}