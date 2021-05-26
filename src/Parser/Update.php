<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an incoming update.
 * @see https://core.telegram.org/bots/api#update
 *
 * @method integer update_id() The update's unique identifier. Update identifiers start from a certain positive number and increase sequentially. This ID becomes especially handy if you're using Webhooks, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
 * @method Message message() Optional. New incoming message of any kind — text, photo, sticker, etc.
 * @method Message edited_message() Optional. New version of a message that is known to the bot and was edited
 * @method Message channel_post() Optional. New incoming channel post of any kind — text, photo, sticker, etc.
 * @method Message edited_channel_post() Optional. New version of a channel post that is known to the bot and was edited
 * ...
 * @method Poll poll() Optional. New poll state. Bots receive only updates about stopped polls and polls, which are sent by the bot
 * @method PollAnswer poll_answer() Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * ...
 */
class Update extends Base
{

  protected $wrapper = [
    'message' => Message::class,
    'edited_message' => Message::class,
    'channel_post' => Message::class,
    'edited_channel_post' => Message::class,
    'poll' => Poll::class,
    'poll_answer' => PollAnswer::class,
  ];

  public function id()
  {
    return $this->update_id();
  }

}