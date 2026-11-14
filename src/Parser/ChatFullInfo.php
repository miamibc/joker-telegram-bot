<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains full information about a chat.
 * @see https://core.telegram.org/bots/api#chatfullinfo
 *
 * @method integer id() Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method string type() Type of the chat, can be either “private”, “group”, “supergroup” or “channel”
 * @method string title() Optional. Title, for supergroups, channels and group chats
 * @method string username() Optional. Username, for private chats, supergroups and channels if available
 * @method string first_name() Optional. First name of the other party in a private chat
 * @method string last_name() Optional. Last name of the other party in a private chat
 * @method bool is_forum() Optional. True, if the supergroup chat is a forum (has topics enabled)
 * @method bool is_direct_messages() Optional. True, if the chat is the direct messages chat of a channel
 * @method integer accent_color_id() Identifier of the accent color for the chat name and backgrounds of the chat photo, reply header, and link preview. See accent colors for more details.
 * @method integer max_reaction_count() The maximum number of reactions that can be set on a message in the chat
 * @method ChatPhoto photo() Optional. Chat photo
 * @method string[] active_usernames() Optional. If non-empty, the list of all active chat usernames; for private chats, supergroups and channels
 * @method Birthdate birthdate() Optional. For private chats, the date of birth of the user
 * @method BusinessIntro business_intro() Optional. For private chats with business accounts, the intro of the business
 * @method BusinessLocation business_location() Optional. For private chats with business accounts, the location of the business
 * @method BusinessOpeningHours business_opening_hours() Optional. For private chats with business accounts, the opening hours of the business
 * @method Chat personal_chat() Optional. For private chats, the personal channel of the user
 * @method Chat parent_chat() Optional. Information about the corresponding channel chat; for direct messages chats only
 * @method ReactionType[] available_reactions() Optional. List of available reactions allowed in the chat. If omitted, then all emoji reactions are allowed.
 * @method string background_custom_emoji_id() Optional. Custom emoji identifier of the emoji chosen by the chat for the reply header and link preview background
 * @method integer profile_accent_color_id() Optional. Identifier of the accent color for the chat's profile background. See profile accent colors for more details.
 * @method string profile_background_custom_emoji_id() Optional. Custom emoji identifier of the emoji chosen by the chat for its profile background
 * @method string emoji_status_custom_emoji_id() Optional. Custom emoji identifier of the emoji status of the chat or the other party in a private chat
 * @method integer emoji_status_expiration_date() Optional. Expiration date of the emoji status of the chat or the other party in a private chat, in Unix time, if any
 * @method string bio() Optional. Bio of the other party in a private chat
 * @method bool has_private_forwards() Optional. True, if privacy settings of the other party in the private chat allows to use tg://user?id=<user_id> links only in chats with the user
 * @method bool has_restricted_voice_and_video_messages() Optional. True, if the privacy settings of the other party restrict sending voice and video note messages in the private chat
 * @method bool join_to_send_messages() Optional. True, if users need to join the supergroup before they can send messages
 * @method bool join_by_request() Optional. True, if all users directly joining the supergroup without using an invite link need to be approved by supergroup administrators
 * @method string description() Optional. Description, for groups, supergroups and channel chats
 * @method string invite_link() Optional. Primary invite link, for groups, supergroups and channel chats
 * @method Message pinned_message() Optional. The most recent pinned message (by sending date)
 * @method ChatPermissions permissions() Optional. Default chat member permissions, for groups and supergroups
 * @method AcceptedGiftTypes accepted_gift_types() Information about types of gifts that are accepted by the chat or by the corresponding user for private chats
 * @method bool can_send_paid_media() Optional. True, if paid media messages can be sent or forwarded to the channel chat. The field is available only for channel chats.
 * @method integer slow_mode_delay() Optional. For supergroups, the minimum allowed delay between consecutive messages sent by each unprivileged user; in seconds
 * @method integer unrestrict_boost_count() Optional. For supergroups, the minimum number of boosts that a non-administrator user needs to add in order to ignore slow mode and chat permissions
 * @method integer message_auto_delete_time() Optional. The time after which all messages sent to the chat will be automatically deleted; in seconds
 * @method bool has_aggressive_anti_spam_enabled() Optional. True, if aggressive anti-spam checks are enabled in the supergroup. The field is only available to chat administrators.
 * @method bool has_hidden_members() Optional. True, if non-administrators can only get the list of bots and administrators in the chat
 * @method bool has_protected_content() Optional. True, if messages from the chat can't be forwarded to other chats
 * @method bool has_visible_history() Optional. True, if new chat members will have access to old messages; available only to chat administrators
 * @method string sticker_set_name() Optional. For supergroups, name of the group sticker set
 * @method bool can_set_sticker_set() Optional. True, if the bot can change the group sticker set
 * @method string custom_emoji_sticker_set_name() Optional. For supergroups, the name of the group's custom emoji sticker set. Custom emoji from this set can be used by all users and bots in the group.
 * @method integer linked_chat_id() Optional. Unique identifier for the linked chat, i.e. the discussion group identifier for a channel and vice versa; for supergroups and channel chats. This identifier may be greater than 32 bits and some programming languages may have difficulty/silent defects in interpreting it. But it is smaller than 52 bits, so a signed 64 bit integer or double-precision float type are safe for storing this identifier.
 * @method ChatLocation location() Optional. For supergroups, the location to which the supergroup is connected
 * @method UserRating rating() Optional. For private chats, the rating of the user if any
 * @method Audio first_profile_audio() Optional. For private chats, the first audio added to the profile of the user
 * @method UniqueGiftColors unique_gift_colors() Optional. The color scheme based on a unique gift that must be used for the chat's name, message replies and link previews
 * @method integer paid_message_star_count() Optional. The number of Telegram Stars a general user has to pay to send a message to the chat
 * @method User guard_bot() Optional. The bot that processes join request queries in the chat. The field is only available to chat administrators.
 * @method Community community() Optional. The Community to which the chat belongs
 * @method TonWalletCard ton_wallet_card() Optional. For private chats, the TON wallet card of the user, which is shown on their profile page
 * @method ChatShareTheme share_theme() Optional. For private chats, supergroups and channels, the theme of the screen for sharing a link to the user or the chat; omitted if the default theme is used
 */
class ChatFullInfo extends Base
{

  protected $wrapper = [
    'photo' => ChatPhoto::class,
    'birthdate' => Birthdate::class,
    'business_intro' => BusinessIntro::class,
    'business_location' => BusinessLocation::class,
    'business_opening_hours' => BusinessOpeningHours::class,
    'personal_chat' => Chat::class,
    'parent_chat' => Chat::class,
    'available_reactions' => ReactionType::class,
    'pinned_message' => Message::class,
    'permissions' => ChatPermissions::class,
    'accepted_gift_types' => AcceptedGiftTypes::class,
    'location' => ChatLocation::class,
    'rating' => UserRating::class,
    'first_profile_audio' => Audio::class,
    'unique_gift_colors' => UniqueGiftColors::class,
    'guard_bot' => User::class,
    'community' => Community::class,
    'ton_wallet_card' => TonWalletCard::class,
    'share_theme' => ChatShareTheme::class,
  ];

}
