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
 * @method CallbackQuery callback_query() Optional. New incoming callback query
 * @method InlineQuery inline_query() Optional. New incoming inline query
 * @method ShippingQuery shipping_query() Optional. New incoming shipping query. Only for invoices with flexible price
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
    'callback_query' => CallbackQuery::class,
    'inline_query' => InlineQuery::class,
    'shipping_query' => ShippingQuery::class,
  ];

  public function id()
  {
    return $this->update_id();
  }


  /**
   * Get all characteristics of update with true/false values
   * @return array
   */
  public function getTags()
  {
    return [
      'Private' => $private = (
        isset($this->data['message']['chat']['type'])
        && in_array( $this->data['message']['chat']['type'], ['private'])
      ),
      'Public'    => !$private,
      'Group'     => isset($this->data['message']['chat']['type'])
                     && in_array( $this->data['message']['chat']['type'], ['group', 'supergroup', 'channel']),
      'Sticker'   => isset($this->data['message']['sticker']),
      'Entities'  => isset($this->data['message']['entities']),
      'Animation' => isset($this->data['message']['animation']),
      'Audio'     => isset($this->data['message']['audio']),
      'Document'  => isset($this->data['message']['document']),
      'Video'     => isset($this->data['message']['video']),
      'Voice'     => isset($this->data['message']['voice']),
      'Contact'   => isset($this->data['message']['contact']),
      'Dice'      => isset($this->data['message']['dice']),
      'Game'      => isset($this->data['message']['game']),
      'Photo'     => isset($this->data['message']['photo']),
      'Caption'   => isset($this->data['message']['caption']),
      'Text'      => isset($this->data['message']['text']),
      'Reply'     => isset($this->data['message']['reply_to_message']),
      'Forward'   => isset($this->data['message']['forward_from'])
                     || isset($this->data['message']['forward_from_chat'])
                     || isset($this->data['message']['forward_from_message_id'])
                     || isset($this->data['message']['forward_date']),
      'Poll'      => isset($this->data['poll']),
      'Answer'    => isset($this->data['poll_answer']),
      'Edit'      => isset($this->data['edited_message'])
                     || isset($this->data['edited_channel_post']),
      'Location'  => isset($this->data['message']['venue'])
                     || isset($this->data['message']['location']),
      'Join'      => isset($this->data['message']['new_chat_member'])
                     || isset($this->data['message']['new_chat_members'])
                     || isset($this->data['message']['new_chat_participant']),
      'Leave'     => isset($this->data['message']['left_chat_member'])
                     || isset($this->data['message']['left_chat_participant']),
      'Pin'       => isset($this->data['message']['pinned_message']),
      'Message'   => isset($this->data['message']),
      'Viabot'    => isset($this->data['message']['via_bot']),
      'Empty'     => empty($this->data),
      'Timer'     => empty($this->data),
      'Callback'  => isset($this->data['callback_query']),
      'Inline'    => isset($this->data['inline_query']),
      'Shipping'  => isset($this->data['shipping_query']),
    ];
  }

  /**
   * check tag exists and it's true
   * Note tag names must start from Uppercase letter
   * @param string $tag
   * @return bool
   */
  public function hasTag( string $tag ) : bool
  {
    $tags = $this->getTags();
    return isset($tags[$tag]) && $tags[$tag];
  }

  public function toJson()
  {
    return json_encode( $this->data );
  }

  public function sendMessage( $chat_id, $text, $options = [] )
  {
    return $this->bot()->sendMessage( $chat_id, $text, $options );
  }

  public function answerMessage( $text, $options = [] )
  {
    if (!isset($this->data['message']['chat']['id'])) return false;
    return $this->bot()->sendMessage( $this->data['message']['chat']['id'], $text, $options );
  }

  public function replyMessage( $text, $options = [] )
  {
    if (!isset($this->data['message']['message_id'],
               $this->data['message']['chat']['id'])) return false;
    $options['reply_to_message_id'] = $this->data['message']['message_id'];
    return $this->bot()->sendMessage( $this->data['message']['chat']['id'], $text, $options );
  }

  public function deleteMessage()
  {
    if (!isset($this->data['message']['chat']['id'], $this->data['message']['message_id'])) return false;
    return $this->bot()->deleteMessage( $this->data['message']['chat']['id'], $this->data['message']['message_id'] );
  }

  public function answerSticker( $file_id, $options = [] )
  {
    if (!isset($this->data['message']['chat']['id'])) return false;
    return $this->bot()->sendSticker( $this->data['message']['chat']['id'], $file_id, $options );
  }

  public function answerPhoto( $file, $options = [])
  {
    if (!isset($this->data['message']['chat']['id'])) return false;
    return $this->bot()->sendPhoto( $this->data['message']['chat']['id'], $file, $options );
  }

  public function forwardMessage( $chat_id , $options = [])
  {
    if (!isset($this->data['message']['chat']['id'], $this->data['message']['message_id'])) return false;
    return $this->bot()->forwardMessage( $chat_id, $this->data['message']['chat']['id'], $this->data['message']['message_id'], $options );
  }

  public function customRequest( $method, $data = [])
  {
    return $this->bot()->customRequest($method, $data);
  }

}