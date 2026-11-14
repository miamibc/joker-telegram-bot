<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents an incoming update. At most one of the optional fields can be present in any given update.
 * @see https://core.telegram.org/bots/api#update
 *
 * @method integer update_id() The update's unique identifier. Update identifiers start from a certain positive number and increase sequentially. This identifier becomes especially handy if you're using webhooks, since it allows you to ignore repeated updates or to restore the correct update sequence, should they get out of order. If there are no new updates for at least a week, then identifier of the next update will be chosen randomly instead of sequentially.
 * @method Message message() Optional. New incoming message of any kind - text, photo, sticker, etc.
 * @method Message edited_message() Optional. New version of a message that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method Message channel_post() Optional. New incoming channel post of any kind - text, photo, sticker, etc.
 * @method Message edited_channel_post() Optional. New version of a channel post that is known to the bot and was edited. This update may at times be triggered by changes to message fields that are either unavailable or not actively used by your bot.
 * @method BusinessConnection business_connection() Optional. The bot was connected to or disconnected from a business account, or a user edited an existing connection with the bot
 * @method Message business_message() Optional. New message from a connected business account
 * @method Message edited_business_message() Optional. New version of a message from a connected business account
 * @method BusinessMessagesDeleted deleted_business_messages() Optional. Messages were deleted from a connected business account
 * @method Message guest_message() Optional. New guest message. The bot can use the field Message.guest_query_id and the method answerGuestQuery to send a message in response.
 * @method MessageReactionUpdated message_reaction() Optional. A reaction to a message was changed by a user. The bot must be an administrator in the chat and must explicitly specify "message_reaction" in the list of allowed_updates to receive these updates. The update isn't received for reactions set by bots.
 * @method MessageReactionCountUpdated message_reaction_count() Optional. Reactions to a message with anonymous reactions were changed. The bot must be an administrator in the chat and must explicitly specify "message_reaction_count" in the list of allowed_updates to receive these updates. The updates are grouped and can be sent with delay up to a few minutes.
 * @method InlineQuery inline_query() Optional. New incoming inline query
 * @method ChosenInlineResult chosen_inline_result() Optional. The result of an inline query that was chosen by a user and sent to their chat partner. Please see our documentation on the feedback collecting for details on how to enable these updates for your bot.
 * @method CallbackQuery callback_query() Optional. New incoming callback query
 * @method WidgetQuery widget_query() Optional. New incoming query from a widget in a message sent by the bot or via the bot
 * @method SharedWidgetQuery shared_widget_query() Optional. New incoming query from a forwarded message with a shared widget
 * @method ShippingQuery shipping_query() Optional. New incoming shipping query. Only for invoices with flexible price.
 * @method PreCheckoutQuery pre_checkout_query() Optional. New incoming pre-checkout query. Contains full information about checkout.
 * @method PaidMediaPurchased purchased_paid_media() Optional. A user purchased paid media with a non-empty payload sent by the bot in a non-channel chat
 * @method Poll poll() Optional. New poll state. Bots receive only updates about manually stopped polls and polls, which are sent by the bot.
 * @method PollAnswer poll_answer() Optional. A user changed their answer in a non-anonymous poll. Bots receive new votes only in polls that were sent by the bot itself.
 * @method ChatMemberUpdated my_chat_member() Optional. The bot's chat member status was updated in a chat. For private chats, this update is received only when the bot is blocked or unblocked by the user.
 * @method ChatMemberUpdated chat_member() Optional. A chat member's status was updated in a chat. The bot must be an administrator in the chat and must explicitly specify "chat_member" in the list of allowed_updates to receive these updates.
 * @method ChatJoinRequest chat_join_request() Optional. A request to join the chat has been sent. The bot must have the can_invite_users administrator right in the chat to receive these updates.
 * @method ChatBoostUpdated chat_boost() Optional. A chat boost was added or changed. The bot must be an administrator in the chat to receive these updates.
 * @method ChatBoostRemoved removed_chat_boost() Optional. A boost was removed from a chat. The bot must be an administrator in the chat to receive these updates.
 * @method ManagedBotUpdated managed_bot() Optional. A new bot was created to be managed by the bot, or token or owner of a managed bot was changed
 * @method BotSubscriptionUpdated subscription() Optional. User payment subscription has changed
 * @method MessageGenerationStopped stopped_message_generation() Optional. A user asked the bot to stop the generation of a message
 */
class Update extends Base
{
  protected $wrapper = [
    'message' => Message::class,
    'edited_message' => Message::class,
    'channel_post' => Message::class,
    'edited_channel_post' => Message::class,
    'business_connection' => BusinessConnection::class,
    'business_message' => Message::class,
    'edited_business_message' => Message::class,
    'deleted_business_messages' => BusinessMessagesDeleted::class,
    'guest_message' => Message::class,
    'message_reaction' => MessageReactionUpdated::class,
    'message_reaction_count' => MessageReactionCountUpdated::class,
    'inline_query' => InlineQuery::class,
    'chosen_inline_result' => ChosenInlineResult::class,
    'callback_query' => CallbackQuery::class,
    'widget_query' => WidgetQuery::class,
    'shared_widget_query' => SharedWidgetQuery::class,
    'shipping_query' => ShippingQuery::class,
    'pre_checkout_query' => PreCheckoutQuery::class,
    'purchased_paid_media' => PaidMediaPurchased::class,
    'poll' => Poll::class,
    'poll_answer' => PollAnswer::class,
    'my_chat_member' => ChatMemberUpdated::class,
    'chat_member' => ChatMemberUpdated::class,
    'chat_join_request' => ChatJoinRequest::class,
    'chat_boost' => ChatBoostUpdated::class,
    'removed_chat_boost' => ChatBoostRemoved::class,
    'managed_bot' => ManagedBotUpdated::class,
    'subscription' => BotSubscriptionUpdated::class,
    'stopped_message_generation' => MessageGenerationStopped::class,
  ];

  const ACTION_TYPING = 'typing',
    ACTION_UPLOAD_PHOTO = 'upload_photo',
    ACTION_RECORD_VIDEO = 'record_video',
    ACTION_UPLOAD_VIDEO = 'upload_video',
    ACTION_RECORD_VOICE = 'record_voice',
    ACTION_UPLOAD_DOCUMENT = 'upload_document',
    ACTION_STICKER = 'choose_sticker',
    ACTION_FIND_LOCATION = 'find_location',
    ACTION_RECORD_VIDEO_NOTE = 'record_video_note'
  ;

  /**
   * alias for update_id
   * @return int
   */
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
      'Forward'   => isset($this->data['message']['forward_origin'])
                  || isset($this->data['message']['forward_from'])
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
      'Checkout'  => isset($this->data['pre_checkout_query']),
      'Purchase'  => isset($this->data['purchased_paid_media']),
      'Invoice'   => isset($this->data['message']['invoice']),
      'Payment'   => isset($this->data['message']['successful_payment']),
      'Refund'    => isset($this->data['message']['refunded_payment']),
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