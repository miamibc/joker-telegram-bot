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
 * @method string id() Unique poll identifier
 * @method string question() Poll question, 1-300 characters
 * @method MessageEntity[] question_entities() Optional. Special entities that appear in the question. Currently, only custom emoji entities are allowed in poll questions
 * @method PollOption[] options() List of poll options
 * @method integer total_voter_count() Total number of users that voted in the poll
 * @method bool is_closed() True, if the poll is closed
 * @method bool is_anonymous() True, if the poll is anonymous
 * @method string type() Poll type, currently can be “regular” or “quiz”
 * @method bool allows_multiple_answers() True, if the poll allows multiple answers
 * @method bool allows_revoting() True, if the poll allows to change the chosen answer options
 * @method bool members_only() True if voting is limited to users who have been members of the chat where the poll was originally sent for more than 24 hours
 * @method string[] country_codes() Optional. A list of two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which users can vote in the poll. The country code “FT” is used for users with anonymous numbers. If omitted, then users from any country can participate in the poll.
 * @method integer[] correct_option_ids() Optional. Array of 0-based identifiers of the correct answer options. Available only for polls in quiz mode which are closed or were sent (not forwarded) by the bot or to the private chat with the bot.
 * @method string explanation() Optional. Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters
 * @method MessageEntity[] explanation_entities() Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the explanation
 * @method PollMedia explanation_media() Optional. Media added to the quiz explanation
 * @method integer open_period() Optional. Amount of time in seconds the poll will be active after creation
 * @method integer close_date() Optional. Point in time (Unix timestamp) when the poll will be automatically closed
 * @method string description() Optional. Description of the poll; for polls inside the Message object only
 * @method MessageEntity[] description_entities() Optional. Special entities like usernames, URLs, bot commands, etc. that appear in the description
 * @method PollMedia media() Optional. Media added to the poll description; for polls inside the Message object only
 * @method integer correct_option_id Optional. 0-based identifier of the correct answer option. Available only for polls in the quiz mode, which are closed, or was sent (not forwarded) by the bot or to the private chat with the bot.
 * @method integer close_period  Optional. Point in time (Unix timestamp) when the poll will be automatically closed
 */
class Poll extends Base
{

  protected $wrapper = [
    'question_entities' => MessageEntity::class,
    'options' => PollOption::class,
    'explanation_entities' => MessageEntity::class,
    'explanation_media' => PollMedia::class,
    'description_entities' => MessageEntity::class,
    'media' => PollMedia::class,
  ];


}