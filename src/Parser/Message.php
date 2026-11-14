<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a message.
 * @see https://core.telegram.org/bots/api#message
 *
 * @method integer message_id() Unique message identifier inside this chat; 0 for ephemeral messages. In specific instances (e.g., a message containing a video sent to a big chat), the server might automatically schedule a message instead of sending it immediately. In such cases, this field will be 0 and the relevant message will be unusable until it is actually sent.
 * @method integer message_thread_id() Optional. Unique identifier of a message thread or forum topic to which the message belongs; for supergroups and private chats only
 * @method DirectMessagesTopic direct_messages_topic() Optional. Information about the direct messages chat topic that contains the message
 * @method User from() Optional. Sender of the message; may be empty for messages sent to channels. For backward compatibility, if the message was sent on behalf of a chat, the field contains a fake sender user in non-channel chats.
 * @method Chat sender_chat() Optional. Sender of the message when sent on behalf of a chat. For example, the supergroup itself for messages sent by its anonymous administrators or a linked channel for messages automatically forwarded to the channel's discussion group. For backward compatibility, if the message was sent on behalf of a chat, the field from contains a fake sender user in non-channel chats.
 * @method integer sender_boost_count() Optional. If the sender of the message boosted the chat, the number of boosts added by the user
 * @method User sender_business_bot() Optional. The bot that actually sent the message on behalf of the business account. Available only for outgoing messages sent on behalf of the connected business account.
 * @method string sender_tag() Optional. Tag or custom title of the sender of the message; for supergroups only
 * @method User receiver_user() Optional. For ephemeral messages, the user who received the message
 * @method integer ephemeral_message_id() Optional. For ephemeral messages, identifier of the ephemeral message inside this chat. The identifier may be reused for another ephemeral message after the message is deleted or expires.
 * @method integer date() Date the message was sent in Unix time. It is always a positive number, representing a valid date.
 * @method string guest_query_id() Optional. The unique identifier for the guest query. Use this identifier with the method answerGuestQuery to send a response message. If non-empty, the message belongs to the chat where the guest bot was summoned, which may not coincide with other existing bot chats sharing the same identifier.
 * @method string business_connection_id() Optional. Unique identifier of the business connection from which the message was received. If non-empty, the message belongs to a chat of the corresponding business account that is independent from any potential bot chat which might share the same identifier.
 * @method Chat chat() Chat the message belongs to
 * @method MessageOrigin forward_origin() Optional. Information about the original message for forwarded messages
 * @method bool is_topic_message() Optional. True, if the message is sent to a topic in a forum supergroup or a private chat with the bot
 * @method bool is_automatic_forward() Optional. True, if the message is a channel post that was automatically forwarded to the connected discussion group
 * @method Message reply_to_message() Optional. For replies in the same chat and message thread, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply. If the message is a reply to an ephemeral message, then this field may be omitted.
 * @method ExternalReplyInfo external_reply() Optional. Information about the message that is being replied to, which may come from another chat or forum topic
 * @method TextQuote quote() Optional. For replies that quote part of the original message, the quoted part of the message
 * @method Story reply_to_story() Optional. For replies to a story, the original story
 * @method integer reply_to_checklist_task_id() Optional. Identifier of the specific checklist task that is being replied to
 * @method string reply_to_poll_option_id() Optional. Persistent identifier of the specific poll option that is being replied to
 * @method User via_bot() Optional. Bot through which the message was sent
 * @method User guest_bot_caller_user() Optional. For a message sent by a guest bot, this is the user whose original message triggered the bot's response
 * @method Chat guest_bot_caller_chat() Optional. For a message sent by a guest bot, this is the chat whose original message triggered the bot's response
 * @method integer edit_date() Optional. Date the message was last edited in Unix time
 * @method bool has_protected_content() Optional. True, if the message can't be forwarded
 * @method bool is_from_offline() Optional. True, if the message was sent by an implicit action, for example, as an away or a greeting business message, or as a scheduled message
 * @method bool is_paid_post() Optional. True, if the message is a paid post. Note that such posts must not be deleted for 24 hours to receive the payment and can't be edited.
 * @method string media_group_id() Optional. The unique identifier inside this chat of a media message group this message belongs to
 * @method string author_signature() Optional. Signature of the post author for messages in channels, or the custom title of an anonymous group administrator
 * @method integer paid_star_count() Optional. The number of Telegram Stars that were paid by the sender of the message to send it
 * @method string text() Optional. For text messages, the actual UTF-8 text of the message
 * @method MessageEntity[] entities() Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method LinkPreviewOptions link_preview_options() Optional. Options used for link preview generation for the message, if it is a text message and link preview options were changed
 * @method SuggestedPostInfo suggested_post_info() Optional. Information about suggested post parameters if the message is a suggested post in a channel direct messages chat. If the message is an approved or declined suggested post, then it can't be edited.
 * @method string effect_id() Optional. Unique identifier of the message effect added to the message
 * @method RichMessage rich_message() Optional. Message is a rich formatted message
 * @method Animation animation() Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set.
 * @method Audio audio() Optional. Message is an audio file, information about the file
 * @method Document document() Optional. Message is a general file, information about the file
 * @method LivePhoto live_photo() Optional. Message is a live photo, information about the live photo. For backward compatibility, when this field is set, the photo field will also be set.
 * @method PaidMediaInfo paid_media() Optional. Message contains paid media; information about the paid media
 * @method PhotoSize[] photo() Optional. Message is a photo, available sizes of the photo
 * @method Sticker sticker() Optional. Message is a sticker, information about the sticker
 * @method Story story() Optional. Message is a forwarded story
 * @method Video video() Optional. Message is a video, information about the video
 * @method VideoNote video_note() Optional. Message is a video note, information about the video message
 * @method Voice voice() Optional. Message is a voice message, information about the file
 * @method Widget widget() Optional. Message is an HTML widget, information about the widget
 * @method string caption() Optional. Caption for the animation, audio, document, live photo, paid media, photo, video, voice, or widget
 * @method MessageEntity[] caption_entities() Optional. For messages with a caption, special entities like usernames, URLs, bot commands, etc. that appear in the caption
 * @method bool show_caption_above_media() Optional. True, if the caption must be shown above the message media or the widget
 * @method bool has_media_spoiler() Optional. True, if the message media is covered by a spoiler animation
 * @method Checklist checklist() Optional. Message is a checklist
 * @method Contact contact() Optional. Message is a shared contact, information about the contact
 * @method Dice dice() Optional. Message is a dice with random value
 * @method Game game() Optional. Message is a game, information about the game. More about games »
 * @method Poll poll() Optional. Message is a native poll, information about the poll
 * @method Venue venue() Optional. Message is a venue, information about the venue. For backward compatibility, when this field is set, the location field will also be set.
 * @method Location location() Optional. Message is a shared location, information about the location
 * @method User[] new_chat_members() Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
 * @method User left_chat_member() Optional. A member was removed from the group, information about them (this member may be the bot itself)
 * @method ChatOwnerLeft chat_owner_left() Optional. Service message: chat owner has left
 * @method ChatOwnerChanged chat_owner_changed() Optional. Service message: chat owner has changed
 * @method string new_chat_title() Optional. A chat title was changed to this value
 * @method PhotoSize[] new_chat_photo() Optional. A chat photo was change to this value
 * @method bool delete_chat_photo() Optional. Service message: the chat photo was deleted
 * @method bool group_chat_created() Optional. Service message: the group has been created
 * @method bool supergroup_chat_created() Optional. Service message: the supergroup has been created. This field can't be received in a message coming through updates, because bot can't be a member of a supergroup when it is created. It can only be found in reply_to_message if someone replies to a very first message in a directly created supergroup.
 * @method bool channel_chat_created() Optional. Service message: the channel has been created. This field can't be received in a message coming through updates, because bot can't be a member of a channel when it is created. It can only be found in reply_to_message if someone replies to a very first message in a channel.
 * @method MessageAutoDeleteTimerChanged message_auto_delete_timer_changed() Optional. Service message: auto-delete timer settings changed in the chat
 * @method integer migrate_to_chat_id() Optional. The group has been migrated to a supergroup with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method integer migrate_from_chat_id() Optional. The supergroup has been migrated from a group with the specified identifier. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method Message pinned_message() Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method Invoice invoice() Optional. Message is an invoice for a payment, information about the invoice. More about payments »
 * @method SuccessfulPayment successful_payment() Optional. Message is a service message about a successful payment, information about the payment. More about payments »
 * @method RefundedPayment refunded_payment() Optional. Message is a service message about a refunded payment, information about the payment. More about payments »
 * @method UsersShared users_shared() Optional. Service message: users were shared with the bot
 * @method ChatShared chat_shared() Optional. Service message: a chat was shared with the bot
 * @method GiftInfo gift() Optional. Service message: a regular gift was sent or received
 * @method UniqueGiftInfo unique_gift() Optional. Service message: a unique gift was sent or received
 * @method GiftInfo gift_upgrade_sent() Optional. Service message: upgrade of a gift was purchased after the gift was sent
 * @method string connected_website() Optional. The domain name of the website on which the user has logged in. More about Telegram Login »
 * @method WriteAccessAllowed write_access_allowed() Optional. Service message: the user allowed the bot to write messages after adding it to the attachment or side menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method requestWriteAccess
 * @method PassportData passport_data() Optional. Telegram Passport data
 * @method ProximityAlertTriggered proximity_alert_triggered() Optional. Service message: a user in the chat triggered another user's proximity alert while sharing Live Location
 * @method ChatBoostAdded boost_added() Optional. Service message: user boosted the chat
 * @method ChatBackground chat_background_set() Optional. Service message: chat background set
 * @method ChecklistTasksDone checklist_tasks_done() Optional. Service message: some tasks in a checklist were marked as done or not done
 * @method ChecklistTasksAdded checklist_tasks_added() Optional. Service message: tasks were added to a checklist
 * @method CommunityChatAdded community_chat_added() Optional. Service message: chat or bot added to a Community
 * @method CommunityChatJoined community_chat_joined() Optional. Service message: chat was joined by a user from a Community
 * @method CommunityChatRemoved community_chat_removed() Optional. Service message: chat or bot removed from a Community
 * @method DirectMessagePriceChanged direct_message_price_changed() Optional. Service message: the price for paid messages in the corresponding direct messages chat of a channel has changed
 * @method ForumTopicCreated forum_topic_created() Optional. Service message: forum topic created
 * @method ForumTopicEdited forum_topic_edited() Optional. Service message: forum topic edited
 * @method ForumTopicClosed forum_topic_closed() Optional. Service message: forum topic closed
 * @method ForumTopicReopened forum_topic_reopened() Optional. Service message: forum topic reopened
 * @method GeneralForumTopicHidden general_forum_topic_hidden() Optional. Service message: the 'General' forum topic hidden
 * @method GeneralForumTopicUnhidden general_forum_topic_unhidden() Optional. Service message: the 'General' forum topic unhidden
 * @method GiveawayCreated giveaway_created() Optional. Service message: a scheduled giveaway was created
 * @method Giveaway giveaway() Optional. The message is a scheduled giveaway message
 * @method GiveawayWinners giveaway_winners() Optional. A giveaway with public winners was completed
 * @method GiveawayCompleted giveaway_completed() Optional. Service message: a giveaway without public winners was completed
 * @method ManagedBotCreated managed_bot_created() Optional. Service message: user created a bot that will be managed by the current bot
 * @method PaidMessagePriceChanged paid_message_price_changed() Optional. Service message: the price for paid messages has changed in the chat
 * @method PaidMessagesWaived paid_messages_waived() Optional. Service message: the sender of the message used a link of the other user, which allows sending messages to the user without paying Telegram Stars
 * @method PollOptionAdded poll_option_added() Optional. Service message: answer option was added to a poll
 * @method PollOptionDeleted poll_option_deleted() Optional. Service message: answer option was deleted from a poll
 * @method SuggestedPostApproved suggested_post_approved() Optional. Service message: a suggested post was approved
 * @method SuggestedPostApprovalFailed suggested_post_approval_failed() Optional. Service message: approval of a suggested post has failed
 * @method SuggestedPostDeclined suggested_post_declined() Optional. Service message: a suggested post was declined
 * @method SuggestedPostPaid suggested_post_paid() Optional. Service message: payment for a suggested post was received
 * @method SuggestedPostRefunded suggested_post_refunded() Optional. Service message: payment for a suggested post was refunded
 * @method TonWalletTransfer ton_wallet_transfer() Optional. Service message: a transfer with a TON wallet
 * @method VideoChatScheduled video_chat_scheduled() Optional. Service message: video chat scheduled
 * @method VideoChatStarted video_chat_started() Optional. Service message: video chat started
 * @method VideoChatEnded video_chat_ended() Optional. Service message: video chat ended
 * @method VideoChatParticipantsInvited video_chat_participants_invited() Optional. Service message: new participants invited to a video chat
 * @method WebAppData web_app_data() Optional. Service message: data sent by a Web App
 * @method InlineKeyboardMarkup reply_markup() Optional. Inline keyboard attached to the message. login_url buttons are represented as ordinary url buttons.
 * @method User forward_from() Optional. For forwarded messages, sender of the original message
 * @method Chat forward_from_chat() Optional. For messages forwarded from channels or from anonymous administrators, information about the original sender chat
 * @method User new_chat_member() Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
 */
class Message extends Base
{

  protected $wrapper = [
    'direct_messages_topic' => DirectMessagesTopic::class,
    'from' => User::class,
    'sender_chat' => Chat::class,
    'sender_business_bot' => User::class,
    'receiver_user' => User::class,
    'chat' => Chat::class,
    'forward_origin' => MessageOrigin::class,
    'reply_to_message' => Message::class,
    'external_reply' => ExternalReplyInfo::class,
    'quote' => TextQuote::class,
    'reply_to_story' => Story::class,
    'via_bot' => User::class,
    'guest_bot_caller_user' => User::class,
    'guest_bot_caller_chat' => Chat::class,
    'entities' => MessageEntity::class,
    'link_preview_options' => LinkPreviewOptions::class,
    'suggested_post_info' => SuggestedPostInfo::class,
    'rich_message' => RichMessage::class,
    'animation' => Animation::class,
    'audio' => Audio::class,
    'document' => Document::class,
    'live_photo' => LivePhoto::class,
    'paid_media' => PaidMediaInfo::class,
    'photo' => PhotoSize::class,
    'sticker' => Sticker::class,
    'story' => Story::class,
    'video' => Video::class,
    'video_note' => VideoNote::class,
    'voice' => Voice::class,
    'widget' => Widget::class,
    'caption_entities' => MessageEntity::class,
    'checklist' => Checklist::class,
    'contact' => Contact::class,
    'dice' => Dice::class,
    'game' => Game::class,
    'poll' => Poll::class,
    'venue' => Venue::class,
    'location' => Location::class,
    'new_chat_members' => User::class,
    'left_chat_member' => User::class,
    'chat_owner_left' => ChatOwnerLeft::class,
    'chat_owner_changed' => ChatOwnerChanged::class,
    'new_chat_photo' => PhotoSize::class,
    'message_auto_delete_timer_changed' => MessageAutoDeleteTimerChanged::class,
    'pinned_message' => Message::class,
    'invoice' => Invoice::class,
    'successful_payment' => SuccessfulPayment::class,
    'refunded_payment' => RefundedPayment::class,
    'users_shared' => UsersShared::class,
    'chat_shared' => ChatShared::class,
    'gift' => GiftInfo::class,
    'unique_gift' => UniqueGiftInfo::class,
    'gift_upgrade_sent' => GiftInfo::class,
    'write_access_allowed' => WriteAccessAllowed::class,
    'passport_data' => PassportData::class,
    'proximity_alert_triggered' => ProximityAlertTriggered::class,
    'boost_added' => ChatBoostAdded::class,
    'chat_background_set' => ChatBackground::class,
    'checklist_tasks_done' => ChecklistTasksDone::class,
    'checklist_tasks_added' => ChecklistTasksAdded::class,
    'community_chat_added' => CommunityChatAdded::class,
    'community_chat_joined' => CommunityChatJoined::class,
    'community_chat_removed' => CommunityChatRemoved::class,
    'direct_message_price_changed' => DirectMessagePriceChanged::class,
    'forum_topic_created' => ForumTopicCreated::class,
    'forum_topic_edited' => ForumTopicEdited::class,
    'forum_topic_closed' => ForumTopicClosed::class,
    'forum_topic_reopened' => ForumTopicReopened::class,
    'general_forum_topic_hidden' => GeneralForumTopicHidden::class,
    'general_forum_topic_unhidden' => GeneralForumTopicUnhidden::class,
    'giveaway_created' => GiveawayCreated::class,
    'giveaway' => Giveaway::class,
    'giveaway_winners' => GiveawayWinners::class,
    'giveaway_completed' => GiveawayCompleted::class,
    'managed_bot_created' => ManagedBotCreated::class,
    'paid_message_price_changed' => PaidMessagePriceChanged::class,
    'paid_messages_waived' => PaidMessagesWaived::class,
    'poll_option_added' => PollOptionAdded::class,
    'poll_option_deleted' => PollOptionDeleted::class,
    'suggested_post_approved' => SuggestedPostApproved::class,
    'suggested_post_approval_failed' => SuggestedPostApprovalFailed::class,
    'suggested_post_declined' => SuggestedPostDeclined::class,
    'suggested_post_paid' => SuggestedPostPaid::class,
    'suggested_post_refunded' => SuggestedPostRefunded::class,
    'ton_wallet_transfer' => TonWalletTransfer::class,
    'video_chat_scheduled' => VideoChatScheduled::class,
    'video_chat_started' => VideoChatStarted::class,
    'video_chat_ended' => VideoChatEnded::class,
    'video_chat_participants_invited' => VideoChatParticipantsInvited::class,
    'web_app_data' => WebAppData::class,
    'reply_markup' => InlineKeyboardMarkup::class,
    'forward_from' => User::class,
    'forward_from_chat' => Chat::class,
    'text' => Text::class,
    'caption' => Text::class,
    'new_chat_member' => User::class,
  ];

  public function id()
  {
    return $this->message_id();
  }

}