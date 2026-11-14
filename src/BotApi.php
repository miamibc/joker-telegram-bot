<?php
/**
 * Telegram Bot API methods for Joker
 *
 * Generated from https://core.telegram.org/bots/api (Bot API 10.4)
 * Required parameters are arguments, optional ones are passed in $options array.
 * Pass \CURLFile as a value to upload a file (request becomes multipart/form-data).
 * Results are wrapped in Joker\Parser classes, false is returned on error.
 * Methods with custom logic are defined in Bot class itself.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

use Joker\Parser\BotAccessSettings;
use Joker\Parser\BotCommand;
use Joker\Parser\BotDescription;
use Joker\Parser\BotName;
use Joker\Parser\BotShortDescription;
use Joker\Parser\BusinessConnection;
use Joker\Parser\ChatAdministratorRights;
use Joker\Parser\ChatFullInfo;
use Joker\Parser\ChatInviteLink;
use Joker\Parser\ChatMember;
use Joker\Parser\ChatShareThemeBackdrops;
use Joker\Parser\File;
use Joker\Parser\ForumTopic;
use Joker\Parser\GameHighScore;
use Joker\Parser\Gifts;
use Joker\Parser\MenuButton;
use Joker\Parser\Message;
use Joker\Parser\MessageId;
use Joker\Parser\OwnedGifts;
use Joker\Parser\Poll;
use Joker\Parser\PreparedInlineMessage;
use Joker\Parser\PreparedKeyboardButton;
use Joker\Parser\SentGuestMessage;
use Joker\Parser\SentWebAppMessage;
use Joker\Parser\StarAmount;
use Joker\Parser\Sticker;
use Joker\Parser\StickerSet;
use Joker\Parser\Story;
use Joker\Parser\UniqueGift;
use Joker\Parser\UserChatBoosts;
use Joker\Parser\UserProfileAudios;
use Joker\Parser\UserProfilePhotos;
use Joker\Parser\WebhookInfo;

trait BotApi
{

  /**
   * Make Telegram Bot API request and wrap the result
   * @param string $method
   * @param array $data
   * @param string|null $class parser class to wrap the result in
   * @param bool $array result is an array of objects
   * @return mixed
   */
  protected function apiCall( string $method, array $data, $class = null, bool $array = false )
  {
    $multipart = false;
    foreach ($data as $value) if ($value instanceof \CURLFile) { $multipart = true; break; }

    if ($multipart)
    {
      foreach ($data as $key => $value)
      {
        if ($value instanceof \CURLFile) continue;
        if (is_bool($value)) $data[$key] = $value ? 'true' : 'false';
        elseif (is_array($value) || is_object($value)) $data[$key] = json_encode($value);
      }
      $result = $this->_requestMultipart( $method, $data );
    }
    else
    {
      $result = $this->_request( $method, $data );
    }

    if ($result === false || !$class || !is_array($result)) return $result;
    if ($array) return array_map(function ($item) use ($class) { return new $class($item, $this); }, $result);
    return new $class( $result, $this );
  }

  /**
   * Use this method to specify a URL and receive incoming updates via an outgoing webhook. Whenever there is an update for the bot, we will send an HTTPS POST request to the specified URL, containing a JSON-serialized Update. In case of an unsuccessful request (a request with response HTTP status code different from 2XY), we will repeat the request and give up after a reasonable amount of attempts. Returns True on success.
   * If you'd like to make sure that the webhook was set by you, you can specify secret data in the parameter secret_token. If specified, the request will contain a header “X-Telegram-Bot-Api-Secret-Token” with the secret token as content.
   * @see https://core.telegram.org/bots/api#setwebhook
   *
   * @param string $url HTTPS URL to send updates to. Use an empty string to remove webhook integration.
   * @param array $options Optional parameters:
   *   - certificate (InputFile) Upload your public key certificate so that the root certificate in use can be checked. See our self-signed guide for details.
   *   - ip_address (String) The fixed IP address which will be used to send webhook requests instead of the IP address resolved through DNS
   *   - max_connections (Integer) The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery, 1-100. Defaults to 40. Use lower values to limit the load on your bot's server, and higher values to increase your bot's throughput.
   *   - allowed_updates (Array of String) A JSON-serialized list of the update types you want your bot to receive. For example, specify ["message", "edited_channel_post", "callback_query"] to only receive updates of these types. See Update for a complete list of available update types. Specify an empty list to receive all update types except chat_member, message_reaction, and message_reaction_count (default). If not specified, the previous setting will be used. Please note that this parameter doesn't affect updates created before the call to the setWebhook, so unwanted updates may be received for a short period of time.
   *   - drop_pending_updates (Boolean) Pass True to drop all pending updates
   *   - secret_token (String) A secret token to be sent in a header “X-Telegram-Bot-Api-Secret-Token” in every webhook request, 1-256 characters. Only characters A-Z, a-z, 0-9, _ and - are allowed. The header is useful to ensure that the request comes from a webhook set by you.
   * @return bool
   */
  public function setWebhook( $url, array $options = [] )
  {
    return $this->apiCall( 'setWebhook', array_merge( ['url' => $url], $options ) );
  }

  /**
   * Use this method to remove webhook integration if you decide to switch back to getUpdates. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletewebhook
   *
   * @param array $options Optional parameters:
   *   - drop_pending_updates (Boolean) Pass True to drop all pending updates
   * @return bool
   */
  public function deleteWebhook( array $options = [] )
  {
    return $this->apiCall( 'deleteWebhook', array_merge( [], $options ) );
  }

  /**
   * Use this method to get current webhook status. Requires no parameters. On success, returns a WebhookInfo object. If the bot is using getUpdates, will return an object with the url field empty.
   * @see https://core.telegram.org/bots/api#getwebhookinfo
   *
   * @return WebhookInfo|false
   */
  public function getWebhookInfo()
  {
    return $this->apiCall( 'getWebhookInfo', [], WebhookInfo::class );
  }

  /**
   * Use this method to log out from the cloud Bot API server before launching the bot locally. You must log out the bot before running it locally, otherwise there is no guarantee that the bot will receive updates. After a successful call, you can immediately log in on a local server, but will not be able to log in back to the cloud Bot API server for 10 minutes. Returns True on success. Requires no parameters.
   * @see https://core.telegram.org/bots/api#logout
   *
   * @return bool
   */
  public function logOut()
  {
    return $this->apiCall( 'logOut', [] );
  }

  /**
   * Use this method to close the bot instance before moving it from one local server to another. You need to delete the webhook before calling this method to ensure that the bot isn't launched again after server restart. The method will return error 429 in the first 10 minutes after the bot is launched. Returns True on success. Requires no parameters.
   * @see https://core.telegram.org/bots/api#close
   *
   * @return bool
   */
  public function close()
  {
    return $this->apiCall( 'close', [] );
  }

  /**
   * Use this method to forward multiple messages of any kind. If some of the specified messages can't be found or forwarded, they are skipped. Service messages and messages with protected content can't be forwarded. Album grouping is kept for forwarded messages. On success, an Array of MessageId of the sent messages is returned.
   * @see https://core.telegram.org/bots/api#forwardmessages
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param int|string $from_chat_id Unique identifier for the chat where the original messages were sent (or username of the target bot, supergroup, or channel in the format @username)
   * @param array $message_ids A JSON-serialized list of 1-100 identifiers of messages in the chat from_chat_id to forward. The identifiers must be specified in a strictly increasing order.
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the messages will be forwarded; required if the messages are forwarded to a direct messages chat
   *   - disable_notification (Boolean) Sends the messages silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the forwarded messages from forwarding and saving
   * @return MessageId[]|false
   */
  public function forwardMessages( $chat_id, $from_chat_id, $message_ids, array $options = [] )
  {
    return $this->apiCall( 'forwardMessages', array_merge( ['chat_id' => $chat_id, 'from_chat_id' => $from_chat_id, 'message_ids' => $message_ids], $options ), MessageId::class, true );
  }

  /**
   * Use this method to copy messages of any kind. Service messages, paid media messages, giveaway messages, giveaway winners messages, invoice messages, and widget messages can't be copied. A quiz poll can be copied only if the value of the field correct_option_ids is known to the bot. The method is analogous to the method forwardMessage, but the copied message doesn't have a link to the original message. Returns the MessageId of the sent message on success.
   * @see https://core.telegram.org/bots/api#copymessage
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param int|string $from_chat_id Unique identifier for the chat where the original message was sent (or username of the target bot, supergroup, or channel in the format @username)
   * @param int $message_id Message identifier in the chat specified in from_chat_id
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - video_start_timestamp (Integer) New start timestamp for the copied video in the message
   *   - caption (String) New caption for media, 0-1024 characters after entities parsing. If not specified, the original caption is kept.
   *   - parse_mode (String) Mode for parsing entities in the new caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the new caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media. Ignored if a new caption isn't specified.
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; only available when copying to private chats
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return MessageId|false
   */
  public function copyMessage( $chat_id, $from_chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'copyMessage', array_merge( ['chat_id' => $chat_id, 'from_chat_id' => $from_chat_id, 'message_id' => $message_id], $options ), MessageId::class );
  }

  /**
   * Use this method to copy messages of any kind. If some of the specified messages can't be found or copied, they are skipped. Service messages, paid media messages, giveaway messages, giveaway winners messages, invoice messages, and widget messages can't be copied. A quiz poll can be copied only if the value of the field correct_option_ids is known to the bot. The method is analogous to the method forwardMessages, but the copied messages don't have a link to the original message. Album grouping is kept for copied messages. On success, an Array of MessageId of the sent messages is returned.
   * @see https://core.telegram.org/bots/api#copymessages
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param int|string $from_chat_id Unique identifier for the chat where the original messages were sent (or username of the target bot, supergroup, or channel in the format @username)
   * @param array $message_ids A JSON-serialized list of 1-100 identifiers of messages in the chat from_chat_id to copy. The identifiers must be specified in a strictly increasing order.
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat
   *   - disable_notification (Boolean) Sends the messages silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent messages from forwarding and saving
   *   - remove_caption (Boolean) Pass True to copy the messages without their captions
   * @return MessageId[]|false
   */
  public function copyMessages( $chat_id, $from_chat_id, $message_ids, array $options = [] )
  {
    return $this->apiCall( 'copyMessages', array_merge( ['chat_id' => $chat_id, 'from_chat_id' => $from_chat_id, 'message_ids' => $message_ids], $options ), MessageId::class, true );
  }

  /**
   * Use this method to send live photos. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendlivephoto
   *
   * @param \CURLFile|string $live_photo Live photo video to send. The video must be no longer than 10 seconds and must not exceed 10 MB in size. Pass a file_id as String to send a video that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. More information on Sending Files ». Sending live photos by a URL is currently unsupported.
   * @param \CURLFile|string $photo The static photo to send. Pass a file_id as String to send a photo that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. More information on Sending Files ». Sending live photos by a URL is currently unsupported.
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - caption (String) Video caption (may also be used when resending videos by file_id), 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the video caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media
   *   - has_spoiler (Boolean) Pass True if the video needs to be covered with a spoiler animation
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendLivePhoto( $live_photo, $photo, array $options = [] )
  {
    return $this->apiCall( 'sendLivePhoto', array_merge( ['live_photo' => $live_photo, 'photo' => $photo], $options ), Message::class );
  }

  /**
   * Use this method to send general files. On success, the sent Message is returned. Bots can currently send files of any type of up to 50 MB in size, this limit may be changed in the future.
   * @see https://core.telegram.org/bots/api#senddocument
   *
   * @param \CURLFile|string $document File to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. More information on Sending Files »
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - thumbnail (InputFile or String) Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files »
   *   - caption (String) Document caption (may also be used when resending documents by file_id), 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the document caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - disable_content_type_detection (Boolean) Disables automatic server-side content type detection for files uploaded using multipart/form-data
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendDocument( $document, array $options = [] )
  {
    return $this->apiCall( 'sendDocument', array_merge( ['document' => $document], $options ), Message::class );
  }

  /**
   * Use this method to send video files, Telegram clients support MPEG4 videos (other formats may be sent as Document). On success, the sent Message is returned. Bots can currently send video files of up to 50 MB in size, this limit may be changed in the future.
   * @see https://core.telegram.org/bots/api#sendvideo
   *
   * @param \CURLFile|string $video Video to send. Pass a file_id as String to send a video that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a video from the Internet, or upload a new video using multipart/form-data. More information on Sending Files »
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - duration (Integer) Duration of sent video in seconds
   *   - width (Integer) Video width
   *   - height (Integer) Video height
   *   - thumbnail (InputFile or String) Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files »
   *   - cover (InputFile or String) Cover for the video in the message. Pass a file_id to send a file that exists on the Telegram servers (recommended), pass an HTTP URL for Telegram to get a file from the Internet, or pass “attach://<file_attach_name>” to upload a new one using multipart/form-data under <file_attach_name> name. More information on Sending Files »
   *   - start_timestamp (Integer) Start timestamp for the video in the message
   *   - caption (String) Video caption (may also be used when resending videos by file_id), 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the video caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media
   *   - has_spoiler (Boolean) Pass True if the video needs to be covered with a spoiler animation
   *   - supports_streaming (Boolean) Pass True if the uploaded video is suitable for streaming
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendVideo( $video, array $options = [] )
  {
    return $this->apiCall( 'sendVideo', array_merge( ['video' => $video], $options ), Message::class );
  }

  /**
   * Use this method to send animation files (GIF or H.264/MPEG-4 AVC video without sound). On success, the sent Message is returned. Bots can currently send animation files of up to 50 MB in size, this limit may be changed in the future.
   * @see https://core.telegram.org/bots/api#sendanimation
   *
   * @param \CURLFile|string $animation Animation to send. Pass a file_id as String to send an animation that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get an animation from the Internet, or upload a new animation using multipart/form-data. More information on Sending Files »
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - duration (Integer) Duration of sent animation in seconds
   *   - width (Integer) Animation width
   *   - height (Integer) Animation height
   *   - thumbnail (InputFile or String) Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files »
   *   - caption (String) Animation caption (may also be used when resending animation by file_id), 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the animation caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media
   *   - has_spoiler (Boolean) Pass True if the animation needs to be covered with a spoiler animation
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendAnimation( $animation, array $options = [] )
  {
    return $this->apiCall( 'sendAnimation', array_merge( ['animation' => $animation], $options ), Message::class );
  }

  /**
   * Use this method to send audio files, if you want Telegram clients to display the file as a playable voice message. For this to work, your audio must be in an .OGG file encoded with OPUS, or in .MP3 format, or in .M4A format (other formats may be sent as Audio or Document). On success, the sent Message is returned. Bots can currently send voice messages of up to 50 MB in size, this limit may be changed in the future.
   * @see https://core.telegram.org/bots/api#sendvoice
   *
   * @param \CURLFile|string $voice Audio file to send. Pass a file_id as String to send a file that exists on the Telegram servers (recommended), pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. More information on Sending Files »
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - caption (String) Voice message caption, 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the voice message caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - duration (Integer) Duration of the voice message in seconds
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendVoice( $voice, array $options = [] )
  {
    return $this->apiCall( 'sendVoice', array_merge( ['voice' => $voice], $options ), Message::class );
  }

  /**
   * Use this method to send a rounded square MPEG4 video of up to 1 minute long. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendvideonote
   *
   * @param \CURLFile|string $video_note Video note to send. Pass a file_id as String to send a video note that exists on the Telegram servers (recommended) or upload a new video using multipart/form-data. More information on Sending Files ». Sending video notes by a URL is currently unsupported.
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - duration (Integer) Duration of sent video in seconds
   *   - length (Integer) Video width and height, i.e. diameter of the video message
   *   - thumbnail (InputFile or String) Thumbnail of the file sent; can be ignored if thumbnail generation for the file is supported server-side. The thumbnail should be in JPEG format and less than 200 kB in size. A thumbnail's width and height should not exceed 320. Ignored if the file is not uploaded using multipart/form-data. Thumbnails can't be reused and can be only uploaded as a new file, so you can pass “attach://<file_attach_name>” if the thumbnail was uploaded using multipart/form-data under <file_attach_name>. More information on Sending Files »
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendVideoNote( $video_note, array $options = [] )
  {
    return $this->apiCall( 'sendVideoNote', array_merge( ['video_note' => $video_note], $options ), Message::class );
  }

  /**
   * Use this method to send a message with an HTML widget. Currently, in the production environment, widgets can be sent only to private chats, and sent widgets automatically have protected content and therefore can't be forwarded. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendwidget
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param string $html HTML code of the widget, 1-32768 bytes. The code must not exceed 4096 bytes when compressed by zlib with the maximum compression level.
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - width (Integer) Width of the widget in logical pixels; omit if the widget must occupy the whole width of the message bubble. The width is clamped by the app to the range from 200 to the maximum width of the message bubble.
   *   - height (Integer) Height of the widget in logical pixels; omit if the height of the widget isn't fixed. The height is clamped by the app to the range 50-600.
   *   - public_data (String) Public data of the widget, 0-1024 bytes, which will be available wherever the widget is shown. If the widget is shared, then all messages with it will have the same public data.
   *   - chat_data (String) Data of the widget, which is specific to the message, 0-1024 bytes
   *   - caption (String) Widget caption, 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the widget caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the widget
   *   - allow_queries (Boolean) Pass True to allow the widget to send queries to the bot
   *   - is_shared (Boolean) Pass True to create a shared widget, which can be sent again using the method sendSharedWidget
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendWidget( $chat_id, $html, array $options = [] )
  {
    return $this->apiCall( 'sendWidget', array_merge( ['chat_id' => $chat_id, 'html' => $html], $options ), Message::class );
  }

  /**
   * Use this method to send again a shared widget created by the bot. Currently, in the production environment, widgets can be sent only to private chats, and sent widgets automatically have protected content and therefore can't be forwarded. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendsharedwidget
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param string $widget_id Unique identifier of the shared widget
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - chat_data (String) Data of the widget, which is specific to the message, 0-1024 bytes
   *   - caption (String) Widget caption, 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the widget caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the widget
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendSharedWidget( $chat_id, $widget_id, array $options = [] )
  {
    return $this->apiCall( 'sendSharedWidget', array_merge( ['chat_id' => $chat_id, 'widget_id' => $widget_id], $options ), Message::class );
  }

  /**
   * Use this method to send paid media. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendpaidmedia
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username. If the chat is a channel, all Telegram Star proceeds from this media will be credited to the chat's balance. Otherwise, they will be credited to the bot's balance.
   * @param int $star_count The number of Telegram Stars that must be paid to buy access to the media; 1-25000
   * @param array $media A JSON-serialized Array describing the media to be sent; up to 10 items
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - payload (String) Bot-defined paid media payload, 0-128 bytes. This will not be displayed to the user, use it for your internal processes.
   *   - caption (String) Media caption, 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the media caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendPaidMedia( $chat_id, $star_count, $media, array $options = [] )
  {
    return $this->apiCall( 'sendPaidMedia', array_merge( ['chat_id' => $chat_id, 'star_count' => $star_count, 'media' => $media], $options ), Message::class );
  }

  /**
   * Use this method to send a group of photos, live photos, videos, documents or audios as an album. Documents and audio files can be only grouped in an album with messages of the same type. On success, an Array of Message objects that were sent is returned.
   * @see https://core.telegram.org/bots/api#sendmediagroup
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param array $media A JSON-serialized Array describing messages to be sent, must include 2-10 items
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the messages will be sent; required if the messages are sent to a direct messages chat
   *   - disable_notification (Boolean) Sends messages silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent messages from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   * @return Message[]|false
   */
  public function sendMediaGroup( $chat_id, $media, array $options = [] )
  {
    return $this->apiCall( 'sendMediaGroup', array_merge( ['chat_id' => $chat_id, 'media' => $media], $options ), Message::class, true );
  }

  /**
   * Use this method to send point on the map. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendlocation
   *
   * @param float $latitude Latitude of the location
   * @param float $longitude Longitude of the location
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - horizontal_accuracy (Float) The radius of uncertainty for the location, measured in meters; 0-1500
   *   - live_period (Integer) Period in seconds during which the location will be updated (see Live Locations), must be between 60 and 86400, or 0x7FFFFFFF for live locations that can be edited indefinitely. Must be 0 for ephemeral messages.
   *   - heading (Integer) For live locations, a direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
   *   - proximity_alert_radius (Integer) For live locations, a maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendLocation( $latitude, $longitude, array $options = [] )
  {
    return $this->apiCall( 'sendLocation', array_merge( ['latitude' => $latitude, 'longitude' => $longitude], $options ), Message::class );
  }

  /**
   * Use this method to send information about a venue. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendvenue
   *
   * @param float $latitude Latitude of the venue
   * @param float $longitude Longitude of the venue
   * @param string $title Name of the venue
   * @param string $address Address of the venue
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - foursquare_id (String) Foursquare identifier of the venue
   *   - foursquare_type (String) Foursquare type of the venue, if known. (For example, “arts_entertainment/default”, “arts_entertainment/aquarium” or “food/icecream”.)
   *   - google_place_id (String) Google Places identifier of the venue
   *   - google_place_type (String) Google Places type of the venue. (See supported types.)
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendVenue( $latitude, $longitude, $title, $address, array $options = [] )
  {
    return $this->apiCall( 'sendVenue', array_merge( ['latitude' => $latitude, 'longitude' => $longitude, 'title' => $title, 'address' => $address], $options ), Message::class );
  }

  /**
   * Use this method to send phone contacts. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendcontact
   *
   * @param string $phone_number Contact's phone number
   * @param string $first_name Contact's first name
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - last_name (String) Contact's last name
   *   - vcard (String) Additional data about the contact in the form of a vCard, 0-2048 bytes
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendContact( $phone_number, $first_name, array $options = [] )
  {
    return $this->apiCall( 'sendContact', array_merge( ['phone_number' => $phone_number, 'first_name' => $first_name], $options ), Message::class );
  }

  /**
   * Use this method to send a native poll. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendpoll
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username. Polls can't be sent to channel direct messages chats.
   * @param string $question Poll question, 1-300 characters
   * @param array $options A JSON-serialized list of 1-12 answer options
   * @param array $params Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - question_parse_mode (String) Mode for parsing entities in the question. See formatting options for more details. Currently, only custom emoji entities are allowed.
   *   - question_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the poll question. It can be specified instead of question_parse_mode.
   *   - is_anonymous (Boolean) True, if the poll needs to be anonymous, defaults to True
   *   - type (String) Poll type, “quiz” or “regular”, defaults to “regular”
   *   - allows_multiple_answers (Boolean) Pass True if the poll allows multiple answers, defaults to False
   *   - allows_revoting (Boolean) Pass True if the poll allows to change chosen answer options, defaults to False for quizzes and to True for regular polls
   *   - shuffle_options (Boolean) Pass True if the poll options must be shown in random order
   *   - allow_adding_options (Boolean) Pass True if answer options can be added to the poll after creation; not supported for anonymous polls and quizzes
   *   - hide_results_until_closes (Boolean) Pass True if poll results must be shown only after the poll closes
   *   - members_only (Boolean) Pass True if voting is limited to users who have been members of the chat where the poll is being sent for more than 24 hours; for channel chats only
   *   - country_codes (Array of String) A JSON-serialized list of 0-12 two-letter ISO 3166-1 alpha-2 country codes indicating the countries from which users can vote in the poll; for channel chats only. Use “FT” as a country code to allow users with anonymous numbers to vote. If omitted or empty, then users from any country can participate in the poll.
   *   - correct_option_ids (Array of Integer) A JSON-serialized list of monotonically increasing 0-based identifiers of the correct answer options, required for polls in quiz mode
   *   - explanation (String) Text that is shown when a user chooses an incorrect answer or taps on the lamp icon in a quiz-style poll, 0-200 characters with at most 2 line feeds after entities parsing
   *   - explanation_parse_mode (String) Mode for parsing entities in the explanation. See formatting options for more details.
   *   - explanation_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the poll explanation. It can be specified instead of explanation_parse_mode.
   *   - explanation_media (InputPollMedia) Media added to the quiz explanation
   *   - open_period (Integer) Amount of time in seconds the poll will be active after creation, 5-2628000. Can't be used together with close_date.
   *   - close_date (Integer) Point in time (Unix timestamp) when the poll will be automatically closed. Must be at least 5 and no more than 2628000 seconds in the future. Can't be used together with open_period.
   *   - is_closed (Boolean) Pass True if the poll needs to be immediately closed. This can be useful for poll preview.
   *   - description (String) Description of the poll to be sent, 0-1024 characters after entities parsing
   *   - description_parse_mode (String) Mode for parsing entities in the poll description. See formatting options for more details.
   *   - description_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the poll description, which can be specified instead of description_parse_mode
   *   - media (InputPollMedia) Media added to the poll description
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendPoll( $chat_id, $question, $options, array $params = [] )
  {
    return $this->apiCall( 'sendPoll', array_merge( ['chat_id' => $chat_id, 'question' => $question, 'options' => $options], $params ), Message::class );
  }

  /**
   * Use this method to send a checklist on behalf of a connected business account. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendchecklist
   *
   * @param string $business_connection_id Unique identifier of the business connection on behalf of which the message will be sent
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot in the format @username
   * @param array $checklist A JSON-serialized object for the checklist to send
   * @param array $options Optional parameters:
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message
   *   - reply_parameters (ReplyParameters) A JSON-serialized object for description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return Message|false
   */
  public function sendChecklist( $business_connection_id, $chat_id, $checklist, array $options = [] )
  {
    return $this->apiCall( 'sendChecklist', array_merge( ['business_connection_id' => $business_connection_id, 'chat_id' => $chat_id, 'checklist' => $checklist], $options ), Message::class );
  }

  /**
   * Use this method to send an animated emoji that will display a random value. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#senddice
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - emoji (String) Emoji on which the dice throw animation is based. Currently, must be one of “🎲”, “🎯”, “🏀”, “⚽”, “🎳”, or “🎰”. Dice can have values 1-6 for “🎲”, “🎯” and “🎳”, values 1-5 for “🏀” and “⚽”, and values 1-64 for “🎰”. Defaults to “🎲”.
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendDice( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'sendDice', array_merge( ['chat_id' => $chat_id], $options ), Message::class );
  }

  /**
   * Use this method to stream a partial message to a user while the message is being generated. Note that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is finalized, you must call sendMessage with the complete message to persist it in the user's chat. Returns True on success.
   * @see https://core.telegram.org/bots/api#sendmessagedraft
   *
   * @param int $chat_id Unique identifier for the target private chat
   * @param int $draft_id Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation.
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread
   *   - text (String) Text of the message to be sent, 0-4096 characters after entities parsing. Pass an empty text to show a “Thinking…” placeholder.
   *   - parse_mode (String) Mode for parsing entities in the message text. See formatting options for more details.
   *   - entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
   *   - can_stop (Boolean) Pass True to show the user a button to stop further drafts. The bot will receive an Update “stopped_message_generation” if the user presses the button.
   *   - keep_on_stop (Boolean) Pass True to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
   * @return bool
   */
  public function sendMessageDraft( $chat_id, $draft_id, array $options = [] )
  {
    return $this->apiCall( 'sendMessageDraft', array_merge( ['chat_id' => $chat_id, 'draft_id' => $draft_id], $options ) );
  }

  /**
   * Use this method when you need to tell the user that something is happening on the bot's side. The status is set for 5 seconds or less (when a message arrives from your bot, Telegram clients clear its typing status). Returns True on success.
   * Example: The ImageBot needs some time to process a request and upload the image. Instead of sending a text message along the lines of “Retrieving image, please wait…”, the bot may use sendChatAction with action = upload_photo. The user will see a “sending photo” status for the bot.
   * We only recommend using this method when a response from the bot will take a noticeable amount of time to arrive.
   * @see https://core.telegram.org/bots/api#sendchataction
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot or supergroup in the format @username. Channel chats and channel direct messages chats aren't supported.
   * @param string $action Type of action to broadcast. Choose one, depending on what the user is about to receive: typing for text messages, upload_photo for photos, record_video or upload_video for videos, record_voice or upload_voice for voice notes, upload_document for general files, choose_sticker for stickers, find_location for location data, record_video_note or upload_video_note for video notes.
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the action will be sent
   *   - message_thread_id (Integer) Unique identifier for the target message thread or topic of a forum; for supergroups and private chats of bots with forum topic mode enabled only
   * @return bool
   */
  public function sendChatAction( $chat_id, $action, array $options = [] )
  {
    return $this->apiCall( 'sendChatAction', array_merge( ['chat_id' => $chat_id, 'action' => $action], $options ) );
  }

  /**
   * Use this method to change the chosen reactions on a message. Service messages of some types can't be reacted to. Automatically forwarded messages from a channel to its discussion group have the same available reactions as messages in the channel. Bots can't use paid reactions. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmessagereaction
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param int $message_id Identifier of the target message. If the message belongs to a media group, the reaction is set to the first non-deleted message in the group instead.
   * @param array $options Optional parameters:
   *   - reaction (Array of ReactionType) A JSON-serialized list of reaction types to set on the message. Currently, as non-premium users, bots can set up to one reaction per message. A custom emoji reaction can be used if it is either already present on the message or explicitly allowed by chat administrators. Paid reactions can't be used by bots.
   *   - is_big (Boolean) Pass True to set the reaction with a big animation
   * @return bool
   */
  public function setMessageReaction( $chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'setMessageReaction', array_merge( ['chat_id' => $chat_id, 'message_id' => $message_id], $options ) );
  }

  /**
   * Use this method to get a list of profile pictures for a user. Returns a UserProfilePhotos object.
   * @see https://core.telegram.org/bots/api#getuserprofilephotos
   *
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - offset (Integer) Sequential number of the first photo to be returned. By default, all photos are returned.
   *   - limit (Integer) Limits the number of photos to be retrieved. Values between 1-100 are accepted. Defaults to 100.
   * @return UserProfilePhotos|false
   */
  public function getUserProfilePhotos( $user_id, array $options = [] )
  {
    return $this->apiCall( 'getUserProfilePhotos', array_merge( ['user_id' => $user_id], $options ), UserProfilePhotos::class );
  }

  /**
   * Use this method to get a list of profile audios for a user. Returns a UserProfileAudios object.
   * @see https://core.telegram.org/bots/api#getuserprofileaudios
   *
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - offset (Integer) Sequential number of the first audio to be returned. By default, all audios are returned.
   *   - limit (Integer) Limits the number of audios to be retrieved. Values between 1-100 are accepted. Defaults to 100.
   * @return UserProfileAudios|false
   */
  public function getUserProfileAudios( $user_id, array $options = [] )
  {
    return $this->apiCall( 'getUserProfileAudios', array_merge( ['user_id' => $user_id], $options ), UserProfileAudios::class );
  }

  /**
   * Changes the emoji status for a given user that previously allowed the bot to manage their emoji status via the Mini App method requestEmojiStatusAccess. Returns True on success.
   * @see https://core.telegram.org/bots/api#setuseremojistatus
   *
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - emoji_status_custom_emoji_id (String) Custom emoji identifier of the emoji status to set. Pass an empty string to remove the status.
   *   - emoji_status_expiration_date (Integer) Expiration date of the emoji status, if any
   * @return bool
   */
  public function setUserEmojiStatus( $user_id, array $options = [] )
  {
    return $this->apiCall( 'setUserEmojiStatus', array_merge( ['user_id' => $user_id], $options ) );
  }

  /**
   * Use this method to get basic information about a file and prepare it for downloading. For the moment, bots can download files of up to 20MB in size. On success, a File object is returned. The file can then be downloaded via the link https://api.telegram.org/file/bot<token>/<file_path>, where <file_path> is taken from the response. It is guaranteed that the link will be valid for at least 1 hour. When the link expires, a new one can be requested by calling getFile again.
   * @see https://core.telegram.org/bots/api#getfile
   *
   * @param string $file_id File identifier to get information about
   * @return File|false
   */
  public function getFile( $file_id )
  {
    return $this->apiCall( 'getFile', ['file_id' => $file_id], File::class );
  }

  /**
   * Use this method to unban a previously banned user in a supergroup or channel. The user will not return to the group or channel automatically, but will be able to join via link, etc. The bot must be an administrator for this to work. By default, this method guarantees that after the call the user is not a member of the chat, but will be able to join it. So if the user is a member of the chat they will also be removed from the chat. If you don't want this, use the parameter only_if_banned. Returns True on success.
   * @see https://core.telegram.org/bots/api#unbanchatmember
   *
   * @param int|string $chat_id Unique identifier for the target group or username of the target supergroup or channel in the format @username
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - only_if_banned (Boolean) Do nothing if the user is not banned
   * @return bool
   */
  public function unbanChatMember( $chat_id, $user_id, array $options = [] )
  {
    return $this->apiCall( 'unbanChatMember', array_merge( ['chat_id' => $chat_id, 'user_id' => $user_id], $options ) );
  }

  /**
   * Use this method to restrict a user in a supergroup. The bot must be an administrator in the supergroup for this to work and must have the appropriate administrator rights. Pass True for all permissions to lift restrictions from a user. Returns True on success.
   * @see https://core.telegram.org/bots/api#restrictchatmember
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $user_id Unique identifier of the target user
   * @param array $permissions A JSON-serialized object for new user permissions
   * @param array $options Optional parameters:
   *   - use_independent_chat_permissions (Boolean) Pass True if chat permissions are set independently. Otherwise, the can_send_other_messages and can_add_web_page_previews permissions will imply the can_send_messages, can_send_audios, can_send_documents, can_send_photos, can_send_videos, can_send_video_notes, and can_send_voice_notes permissions; the can_send_polls permission will imply the can_send_messages permission.
   *   - until_date (Integer) Date when restrictions will be lifted for the user; Unix time. If user is restricted for more than 366 days or less than 30 seconds from the current time, they are considered to be restricted forever.
   * @return bool
   */
  public function restrictChatMember( $chat_id, $user_id, $permissions, array $options = [] )
  {
    return $this->apiCall( 'restrictChatMember', array_merge( ['chat_id' => $chat_id, 'user_id' => $user_id, 'permissions' => $permissions], $options ) );
  }

  /**
   * Use this method to promote or demote a user in a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Pass False for all boolean parameters to demote a user. Returns True on success.
   * @see https://core.telegram.org/bots/api#promotechatmember
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - is_anonymous (Boolean) Pass True if the administrator's presence in the chat is hidden
   *   - can_manage_chat (Boolean) Pass True if the administrator can access the chat event log, get boost list, see hidden supergroup and channel members, report spam messages, ignore slow mode, and send messages to the chat without paying Telegram Stars. Implied by any other administrator privilege.
   *   - can_delete_messages (Boolean) Pass True if the administrator can delete messages of other users
   *   - can_manage_video_chats (Boolean) Pass True if the administrator can manage video chats
   *   - can_restrict_members (Boolean) Pass True if the administrator can restrict, ban or unban chat members, or access supergroup statistics. For backward compatibility, defaults to True for promotions of channel administrators.
   *   - can_promote_members (Boolean) Pass True if the administrator can add new administrators with a subset of their own privileges or demote administrators that they have promoted, directly or indirectly (promoted by administrators that were appointed by him)
   *   - can_change_info (Boolean) Pass True if the administrator can change chat title, photo and other settings
   *   - can_invite_users (Boolean) Pass True if the administrator can invite new users to the chat
   *   - can_post_stories (Boolean) Pass True if the administrator can post stories to the chat
   *   - can_edit_stories (Boolean) Pass True if the administrator can edit stories posted by other users, post stories to the chat page, pin chat stories, and access the chat's story archive
   *   - can_delete_stories (Boolean) Pass True if the administrator can delete stories posted by other users
   *   - can_post_messages (Boolean) Pass True if the administrator can post messages in the channel, approve suggested posts, or access channel statistics; for channels only
   *   - can_edit_messages (Boolean) Pass True if the administrator can edit messages of other users and can pin messages; for channels only
   *   - can_pin_messages (Boolean) Pass True if the administrator can pin messages; for supergroups only
   *   - can_manage_topics (Boolean) Pass True if the user is allowed to create, rename, close, and reopen forum topics; for supergroups only
   *   - can_manage_direct_messages (Boolean) Pass True if the administrator can manage direct messages within the channel and decline suggested posts; for channels only
   *   - can_manage_tags (Boolean) Pass True if the administrator can edit the tags of regular members; for groups and supergroups only
   *   - can_send_welcome_messages (Boolean) Pass True if the administrator can manage chat welcome messages or directly send them in the case of bots
   * @return bool
   */
  public function promoteChatMember( $chat_id, $user_id, array $options = [] )
  {
    return $this->apiCall( 'promoteChatMember', array_merge( ['chat_id' => $chat_id, 'user_id' => $user_id], $options ) );
  }

  /**
   * Use this method to set a custom title for an administrator in a supergroup promoted by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatadministratorcustomtitle
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $user_id Unique identifier of the target user
   * @param string $custom_title New custom title for the administrator; 0-16 characters, emoji are not allowed
   * @return bool
   */
  public function setChatAdministratorCustomTitle( $chat_id, $user_id, $custom_title )
  {
    return $this->apiCall( 'setChatAdministratorCustomTitle', ['chat_id' => $chat_id, 'user_id' => $user_id, 'custom_title' => $custom_title] );
  }

  /**
   * Use this method to set a tag for a regular member in a group or a supergroup. The bot must be an administrator in the chat for this to work and must have the can_manage_tags administrator right. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatmembertag
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - tag (String) New tag for the member; 0-16 characters, emoji are not allowed
   * @return bool
   */
  public function setChatMemberTag( $chat_id, $user_id, array $options = [] )
  {
    return $this->apiCall( 'setChatMemberTag', array_merge( ['chat_id' => $chat_id, 'user_id' => $user_id], $options ) );
  }

  /**
   * Use this method to ban a channel chat in a supergroup or a channel. Until the chat is unbanned, the owner of the banned chat won't be able to send messages on behalf of any of their channels. The bot must be an administrator in the supergroup or channel for this to work and must have the appropriate administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#banchatsenderchat
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param int $sender_chat_id Unique identifier of the target sender chat
   * @return bool
   */
  public function banChatSenderChat( $chat_id, $sender_chat_id )
  {
    return $this->apiCall( 'banChatSenderChat', ['chat_id' => $chat_id, 'sender_chat_id' => $sender_chat_id] );
  }

  /**
   * Use this method to unban a previously banned channel chat in a supergroup or channel. The bot must be an administrator for this to work and must have the appropriate administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#unbanchatsenderchat
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param int $sender_chat_id Unique identifier of the target sender chat
   * @return bool
   */
  public function unbanChatSenderChat( $chat_id, $sender_chat_id )
  {
    return $this->apiCall( 'unbanChatSenderChat', ['chat_id' => $chat_id, 'sender_chat_id' => $sender_chat_id] );
  }

  /**
   * Use this method to set default chat permissions for all members. The bot must be an administrator in the group or a supergroup for this to work and must have the can_restrict_members administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatpermissions
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param array $permissions A JSON-serialized object for new default chat permissions
   * @param array $options Optional parameters:
   *   - use_independent_chat_permissions (Boolean) Pass True if chat permissions are set independently. Otherwise, the can_send_other_messages and can_add_web_page_previews permissions will imply the can_send_messages, can_send_audios, can_send_documents, can_send_photos, can_send_videos, can_send_video_notes, and can_send_voice_notes permissions; the can_send_polls permission will imply the can_send_messages permission.
   * @return bool
   */
  public function setChatPermissions( $chat_id, $permissions, array $options = [] )
  {
    return $this->apiCall( 'setChatPermissions', array_merge( ['chat_id' => $chat_id, 'permissions' => $permissions], $options ) );
  }

  /**
   * Use this method to generate a new primary invite link for a chat; any previously generated primary link is revoked. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the new invite link as String on success.
   * @see https://core.telegram.org/bots/api#exportchatinvitelink
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @return string|false
   */
  public function exportChatInviteLink( $chat_id )
  {
    return $this->apiCall( 'exportChatInviteLink', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to create an additional invite link for a chat. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. The link can be revoked using the method revokeChatInviteLink. Returns the new invite link as ChatInviteLink object.
   * @see https://core.telegram.org/bots/api#createchatinvitelink
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param array $options Optional parameters:
   *   - name (String) Invite link name; 0-32 characters
   *   - expire_date (Integer) Point in time (Unix timestamp) when the link will expire
   *   - member_limit (Integer) The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
   *   - creates_join_request (Boolean) True, if users joining the chat via the link need to be approved by chat administrators. If True, member_limit can't be specified.
   * @return ChatInviteLink|false
   */
  public function createChatInviteLink( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'createChatInviteLink', array_merge( ['chat_id' => $chat_id], $options ), ChatInviteLink::class );
  }

  /**
   * Use this method to edit a non-primary invite link created by the bot. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the edited invite link as a ChatInviteLink object.
   * @see https://core.telegram.org/bots/api#editchatinvitelink
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param string $invite_link The invite link to edit
   * @param array $options Optional parameters:
   *   - name (String) Invite link name; 0-32 characters
   *   - expire_date (Integer) Point in time (Unix timestamp) when the link will expire
   *   - member_limit (Integer) The maximum number of users that can be members of the chat simultaneously after joining the chat via this invite link; 1-99999
   *   - creates_join_request (Boolean) True, if users joining the chat via the link need to be approved by chat administrators. If True, member_limit can't be specified.
   * @return ChatInviteLink|false
   */
  public function editChatInviteLink( $chat_id, $invite_link, array $options = [] )
  {
    return $this->apiCall( 'editChatInviteLink', array_merge( ['chat_id' => $chat_id, 'invite_link' => $invite_link], $options ), ChatInviteLink::class );
  }

  /**
   * Use this method to create a subscription invite link for a channel chat. The bot must have the can_invite_users administrator rights. The link can be edited using the method editChatSubscriptionInviteLink or revoked using the method revokeChatInviteLink. Returns the new invite link as a ChatInviteLink object.
   * @see https://core.telegram.org/bots/api#createchatsubscriptioninvitelink
   *
   * @param int|string $chat_id Unique identifier for the target channel chat or username of the target channel in the format @username
   * @param int $subscription_period The number of seconds the subscription will be active for before the next payment. Currently, it must always be 2592000 (30 days).
   * @param int $subscription_price The amount of Telegram Stars a user must pay initially and after each subsequent subscription period to be a member of the chat; 1-10000
   * @param array $options Optional parameters:
   *   - name (String) Invite link name; 0-32 characters
   * @return ChatInviteLink|false
   */
  public function createChatSubscriptionInviteLink( $chat_id, $subscription_period, $subscription_price, array $options = [] )
  {
    return $this->apiCall( 'createChatSubscriptionInviteLink', array_merge( ['chat_id' => $chat_id, 'subscription_period' => $subscription_period, 'subscription_price' => $subscription_price], $options ), ChatInviteLink::class );
  }

  /**
   * Use this method to edit a subscription invite link created by the bot. The bot must have the can_invite_users administrator rights. Returns the edited invite link as a ChatInviteLink object.
   * @see https://core.telegram.org/bots/api#editchatsubscriptioninvitelink
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param string $invite_link The invite link to edit
   * @param array $options Optional parameters:
   *   - name (String) Invite link name; 0-32 characters
   * @return ChatInviteLink|false
   */
  public function editChatSubscriptionInviteLink( $chat_id, $invite_link, array $options = [] )
  {
    return $this->apiCall( 'editChatSubscriptionInviteLink', array_merge( ['chat_id' => $chat_id, 'invite_link' => $invite_link], $options ), ChatInviteLink::class );
  }

  /**
   * Use this method to revoke an invite link created by the bot. If the primary link is revoked, a new link is automatically generated. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns the revoked invite link as ChatInviteLink object.
   * @see https://core.telegram.org/bots/api#revokechatinvitelink
   *
   * @param int|string $chat_id Unique identifier of the target chat or username of the target channel in the format @username
   * @param string $invite_link The invite link to revoke
   * @return ChatInviteLink|false
   */
  public function revokeChatInviteLink( $chat_id, $invite_link )
  {
    return $this->apiCall( 'revokeChatInviteLink', ['chat_id' => $chat_id, 'invite_link' => $invite_link], ChatInviteLink::class );
  }

  /**
   * Use this method to approve a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
   * @see https://core.telegram.org/bots/api#approvechatjoinrequest
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param int $user_id Unique identifier of the target user
   * @return bool
   */
  public function approveChatJoinRequest( $chat_id, $user_id )
  {
    return $this->apiCall( 'approveChatJoinRequest', ['chat_id' => $chat_id, 'user_id' => $user_id] );
  }

  /**
   * Use this method to decline a chat join request. The bot must be an administrator in the chat for this to work and must have the can_invite_users administrator right. Returns True on success.
   * @see https://core.telegram.org/bots/api#declinechatjoinrequest
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param int $user_id Unique identifier of the target user
   * @return bool
   */
  public function declineChatJoinRequest( $chat_id, $user_id )
  {
    return $this->apiCall( 'declineChatJoinRequest', ['chat_id' => $chat_id, 'user_id' => $user_id] );
  }

  /**
   * Use this method to process a received chat join request query. Returns True on success.
   * @see https://core.telegram.org/bots/api#answerchatjoinrequestquery
   *
   * @param string $chat_join_request_query_id Unique identifier of the join request query
   * @param string $result Result of the query. Must be either “approve” to allow the user to join the chat, “decline” to disallow the user to join the chat, or “queue” to leave the decision to other administrators.
   * @return bool
   */
  public function answerChatJoinRequestQuery( $chat_join_request_query_id, $result )
  {
    return $this->apiCall( 'answerChatJoinRequestQuery', ['chat_join_request_query_id' => $chat_join_request_query_id, 'result' => $result] );
  }

  /**
   * Use this method to process a received chat join request query by showing a Mini App to the user before deciding the outcome. Call answerChatJoinRequestQuery to resolve the join request query based on the user interaction with the Mini App. Returns True on success.
   * @see https://core.telegram.org/bots/api#sendchatjoinrequestwebapp
   *
   * @param string $chat_join_request_query_id Unique identifier of the join request query
   * @param string $web_app_url An HTTPS URL of a Web App to be opened with additional data as specified in Initializing Web Apps
   * @return bool
   */
  public function sendChatJoinRequestWebApp( $chat_join_request_query_id, $web_app_url )
  {
    return $this->apiCall( 'sendChatJoinRequestWebApp', ['chat_join_request_query_id' => $chat_join_request_query_id, 'web_app_url' => $web_app_url] );
  }

  /**
   * Use this method to set a new profile photo for the chat. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatphoto
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param \CURLFile $photo New chat photo, uploaded using multipart/form-data
   * @return bool
   */
  public function setChatPhoto( $chat_id, $photo )
  {
    return $this->apiCall( 'setChatPhoto', ['chat_id' => $chat_id, 'photo' => $photo] );
  }

  /**
   * Use this method to delete a chat photo. Photos can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletechatphoto
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @return bool
   */
  public function deleteChatPhoto( $chat_id )
  {
    return $this->apiCall( 'deleteChatPhoto', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to change the title of a chat. Titles can't be changed for private chats. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchattitle
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param string $title New chat title, 1-128 characters
   * @return bool
   */
  public function setChatTitle( $chat_id, $title )
  {
    return $this->apiCall( 'setChatTitle', ['chat_id' => $chat_id, 'title' => $title] );
  }

  /**
   * Use this method to change the description of a group, a supergroup or a channel. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatdescription
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param array $options Optional parameters:
   *   - description (String) New chat description, 0-255 characters
   * @return bool
   */
  public function setChatDescription( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'setChatDescription', array_merge( ['chat_id' => $chat_id], $options ) );
  }

  /**
   * Use this method to add a message to the list of pinned messages in a chat. In private chats and channel direct messages chats, all non-service messages can be pinned. Conversely, the bot must be an administrator with the 'can_pin_messages' right or the 'can_edit_messages' right to pin messages in groups and channels respectively. Returns True on success.
   * @see https://core.telegram.org/bots/api#pinchatmessage
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param int $message_id Identifier of a message to pin
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be pinned
   *   - disable_notification (Boolean) Pass True if it is not necessary to send a notification to all chat members about the new pinned message. Notifications are always disabled in channels and private chats.
   * @return bool
   */
  public function pinChatMessage( $chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'pinChatMessage', array_merge( ['chat_id' => $chat_id, 'message_id' => $message_id], $options ) );
  }

  /**
   * Use this method to remove a message from the list of pinned messages in a chat. In private chats and channel direct messages chats, all messages can be unpinned. Conversely, the bot must be an administrator with the 'can_pin_messages' right or the 'can_edit_messages' right to unpin messages in groups and channels respectively. Returns True on success.
   * @see https://core.telegram.org/bots/api#unpinchatmessage
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be unpinned
   *   - message_id (Integer) Identifier of the message to unpin. Required if business_connection_id is specified. If not specified, the most recent pinned message (by sending date) will be unpinned.
   * @return bool
   */
  public function unpinChatMessage( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'unpinChatMessage', array_merge( ['chat_id' => $chat_id], $options ) );
  }

  /**
   * Use this method to clear the list of pinned messages in a chat. In private chats and channel direct messages chats, no additional rights are required to unpin all pinned messages. Conversely, the bot must be an administrator with the 'can_pin_messages' right or the 'can_edit_messages' right to unpin all pinned messages in groups and channels respectively. Returns True on success.
   * @see https://core.telegram.org/bots/api#unpinallchatmessages
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @return bool
   */
  public function unpinAllChatMessages( $chat_id )
  {
    return $this->apiCall( 'unpinAllChatMessages', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method for your bot to leave a group, supergroup or channel. Returns True on success.
   * @see https://core.telegram.org/bots/api#leavechat
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel in the format @username. Channel direct messages chats aren't supported; leave the corresponding channel instead.
   * @return bool
   */
  public function leaveChat( $chat_id )
  {
    return $this->apiCall( 'leaveChat', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to get up-to-date information about the chat. Returns a ChatFullInfo object on success.
   * @see https://core.telegram.org/bots/api#getchat
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel in the format @username
   * @return ChatFullInfo|false
   */
  public function getChat( $chat_id )
  {
    return $this->apiCall( 'getChat', ['chat_id' => $chat_id], ChatFullInfo::class );
  }

  /**
   * Use this method to get a list of administrators in a chat. Returns an Array of ChatMember objects.
   * @see https://core.telegram.org/bots/api#getchatadministrators
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel in the format @username
   * @param array $options Optional parameters:
   *   - return_bots (Boolean) Pass True to additionally receive all bots that are administrators of the chat. By default, bots other than the current bot are omitted.
   * @return ChatMember[]|false
   */
  public function getChatAdministrators( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'getChatAdministrators', array_merge( ['chat_id' => $chat_id], $options ), ChatMember::class, true );
  }

  /**
   * Use this method to get the number of members in a chat. Returns Integer on success.
   * @see https://core.telegram.org/bots/api#getchatmembercount
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel in the format @username
   * @return integer|false
   */
  public function getChatMemberCount( $chat_id )
  {
    return $this->apiCall( 'getChatMemberCount', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to get information about a member of a chat. The method is only guaranteed to work for other users if the bot is an administrator in the chat. Returns a ChatMember object on success.
   * @see https://core.telegram.org/bots/api#getchatmember
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup or channel in the format @username
   * @param int $user_id Unique identifier of the target user
   * @return ChatMember|false
   */
  public function getChatMember( $chat_id, $user_id )
  {
    return $this->apiCall( 'getChatMember', ['chat_id' => $chat_id, 'user_id' => $user_id], ChatMember::class );
  }

  /**
   * Use this method to get the list of backdrops, which can be used as the background of a theme of the screen for sharing a link to a user or a chat. Requires no parameters. Returns a ChatShareThemeBackdrops object.
   * @see https://core.telegram.org/bots/api#getchatsharethemebackdrops
   *
   * @return ChatShareThemeBackdrops|false
   */
  public function getChatShareThemeBackdrops()
  {
    return $this->apiCall( 'getChatShareThemeBackdrops', [], ChatShareThemeBackdrops::class );
  }

  /**
   * Use this method to get the last messages from the personal chat (i.e., the chat currently added to their profile) of a given user. On success, an Array of Message objects is returned.
   * @see https://core.telegram.org/bots/api#getuserpersonalchatmessages
   *
   * @param int $user_id Unique identifier for the target user
   * @param int $limit The maximum number of messages to return; 1-20
   * @return Message[]|false
   */
  public function getUserPersonalChatMessages( $user_id, $limit )
  {
    return $this->apiCall( 'getUserPersonalChatMessages', ['user_id' => $user_id, 'limit' => $limit], Message::class, true );
  }

  /**
   * Use this method to set a new group sticker set for a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatstickerset
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param string $sticker_set_name Name of the sticker set to be set as the group sticker set
   * @return bool
   */
  public function setChatStickerSet( $chat_id, $sticker_set_name )
  {
    return $this->apiCall( 'setChatStickerSet', ['chat_id' => $chat_id, 'sticker_set_name' => $sticker_set_name] );
  }

  /**
   * Use this method to delete a group sticker set from a supergroup. The bot must be an administrator in the chat for this to work and must have the appropriate administrator rights. Use the field can_set_sticker_set optionally returned in getChat requests to check if the bot can use this method. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletechatstickerset
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @return bool
   */
  public function deleteChatStickerSet( $chat_id )
  {
    return $this->apiCall( 'deleteChatStickerSet', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to get custom emoji stickers, which can be used as a forum topic icon by any user. Requires no parameters. Returns an Array of Sticker objects.
   * @see https://core.telegram.org/bots/api#getforumtopiciconstickers
   *
   * @return Sticker[]|false
   */
  public function getForumTopicIconStickers()
  {
    return $this->apiCall( 'getForumTopicIconStickers', [], Sticker::class, true );
  }

  /**
   * Use this method to create a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator right. In the case of a private chat, a link to the topic can be created in the format https://t.me/<bot_username>/<message_thread_id>; the link opens the topic only for the user. Returns information about the created topic as a ForumTopic object.
   * @see https://core.telegram.org/bots/api#createforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param string $name Topic name, 1-128 characters
   * @param array $options Optional parameters:
   *   - icon_color (Integer) Color of the topic icon in RGB format. Currently, must be one of 7322096 (0x6FB9F0), 16766590 (0xFFD67E), 13338331 (0xCB86DB), 9367192 (0x8EEE98), 16749490 (0xFF93B2), or 16478047 (0xFB6F5F).
   *   - icon_custom_emoji_id (String) Unique identifier of the custom emoji shown as the topic icon. Use getForumTopicIconStickers to get all allowed custom emoji identifiers.
   * @return ForumTopic|false
   */
  public function createForumTopic( $chat_id, $name, array $options = [] )
  {
    return $this->apiCall( 'createForumTopic', array_merge( ['chat_id' => $chat_id, 'name' => $name], $options ), ForumTopic::class );
  }

  /**
   * Use this method to edit name and icon of a topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
   * @see https://core.telegram.org/bots/api#editforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
   * @param array $options Optional parameters:
   *   - name (String) New topic name, 0-128 characters. If not specified or empty, the current name of the topic will be kept.
   *   - icon_custom_emoji_id (String) New unique identifier of the custom emoji shown as the topic icon. Use getForumTopicIconStickers to get all allowed custom emoji identifiers. Pass an empty string to remove the icon. If not specified, the current icon will be kept.
   * @return bool
   */
  public function editForumTopic( $chat_id, $message_thread_id, array $options = [] )
  {
    return $this->apiCall( 'editForumTopic', array_merge( ['chat_id' => $chat_id, 'message_thread_id' => $message_thread_id], $options ) );
  }

  /**
   * Use this method to close an open topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
   * @see https://core.telegram.org/bots/api#closeforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
   * @return bool
   */
  public function closeForumTopic( $chat_id, $message_thread_id )
  {
    return $this->apiCall( 'closeForumTopic', ['chat_id' => $chat_id, 'message_thread_id' => $message_thread_id] );
  }

  /**
   * Use this method to reopen a closed topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights, unless it is the creator of the topic. Returns True on success.
   * @see https://core.telegram.org/bots/api#reopenforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
   * @return bool
   */
  public function reopenForumTopic( $chat_id, $message_thread_id )
  {
    return $this->apiCall( 'reopenForumTopic', ['chat_id' => $chat_id, 'message_thread_id' => $message_thread_id] );
  }

  /**
   * Use this method to delete a forum topic along with all its messages in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_delete_messages administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#deleteforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
   * @return bool
   */
  public function deleteForumTopic( $chat_id, $message_thread_id )
  {
    return $this->apiCall( 'deleteForumTopic', ['chat_id' => $chat_id, 'message_thread_id' => $message_thread_id] );
  }

  /**
   * Use this method to clear the list of pinned messages in a forum topic in a forum supergroup chat or a private chat with a user. In the case of a supergroup chat the bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
   * @see https://core.telegram.org/bots/api#unpinallforumtopicmessages
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $message_thread_id Unique identifier for the target message thread of the forum topic
   * @return bool
   */
  public function unpinAllForumTopicMessages( $chat_id, $message_thread_id )
  {
    return $this->apiCall( 'unpinAllForumTopicMessages', ['chat_id' => $chat_id, 'message_thread_id' => $message_thread_id] );
  }

  /**
   * Use this method to edit the name of the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#editgeneralforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param string $name New topic name, 1-128 characters
   * @return bool
   */
  public function editGeneralForumTopic( $chat_id, $name )
  {
    return $this->apiCall( 'editGeneralForumTopic', ['chat_id' => $chat_id, 'name' => $name] );
  }

  /**
   * Use this method to close an open 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#closegeneralforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @return bool
   */
  public function closeGeneralForumTopic( $chat_id )
  {
    return $this->apiCall( 'closeGeneralForumTopic', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to reopen a closed 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically unhidden if it was hidden. Returns True on success.
   * @see https://core.telegram.org/bots/api#reopengeneralforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @return bool
   */
  public function reopenGeneralForumTopic( $chat_id )
  {
    return $this->apiCall( 'reopenGeneralForumTopic', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to hide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. The topic will be automatically closed if it was open. Returns True on success.
   * @see https://core.telegram.org/bots/api#hidegeneralforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @return bool
   */
  public function hideGeneralForumTopic( $chat_id )
  {
    return $this->apiCall( 'hideGeneralForumTopic', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to unhide the 'General' topic in a forum supergroup chat. The bot must be an administrator in the chat for this to work and must have the can_manage_topics administrator rights. Returns True on success.
   * @see https://core.telegram.org/bots/api#unhidegeneralforumtopic
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @return bool
   */
  public function unhideGeneralForumTopic( $chat_id )
  {
    return $this->apiCall( 'unhideGeneralForumTopic', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to clear the list of pinned messages in a General forum topic. The bot must be an administrator in the chat for this to work and must have the can_pin_messages administrator right in the supergroup. Returns True on success.
   * @see https://core.telegram.org/bots/api#unpinallgeneralforumtopicmessages
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @return bool
   */
  public function unpinAllGeneralForumTopicMessages( $chat_id )
  {
    return $this->apiCall( 'unpinAllGeneralForumTopicMessages', ['chat_id' => $chat_id] );
  }

  /**
   * Use this method to send answers to callback queries sent from inline keyboards. The answer will be displayed to the user as a notification at the top of the chat screen or as an alert. On success, True is returned.
   * Alternatively, the user can be redirected to the specified Game URL. For this option to work, you must first create a game for your bot via @BotFather and accept the terms. Otherwise, you may use links like t.me/your_bot?start=XXXX that open your bot with a parameter.
   * @see https://core.telegram.org/bots/api#answercallbackquery
   *
   * @param string $callback_query_id Unique identifier for the query to be answered
   * @param array $options Optional parameters:
   *   - text (String) Text of the notification. If not specified, nothing will be shown to the user, 0-200 characters.
   *   - show_alert (Boolean) If True, an alert will be shown by the client instead of a notification at the top of the chat screen. Defaults to False.
   *   - url (String) URL that will be opened by the user's client. If you have created a Game and accepted the conditions via @BotFather, specify the URL that opens your game - note that this will only work if the query comes from a callback_game button. Otherwise, you may use links like t.me/your_bot?start=XXXX that open your bot with a parameter.
   *   - cache_time (Integer) The maximum amount of time in seconds that the result of the callback query may be cached client-side. Defaults to 0.
   * @return bool
   */
  public function answerCallbackQuery( $callback_query_id, array $options = [] )
  {
    return $this->apiCall( 'answerCallbackQuery', array_merge( ['callback_query_id' => $callback_query_id], $options ) );
  }

  /**
   * Use this method to send an answer to a query from a widget. The query must be answered within 15 seconds. The answer is received only by the widget that sent the query and doesn't change data of the widget; use editMessageWidgetData or editSharedWidgetData to change it. On success, True is returned.
   * @see https://core.telegram.org/bots/api#answerwidgetquery
   *
   * @param string $widget_query_id Unique identifier for the query to be answered
   * @param array $options Optional parameters:
   *   - data (String) Data to be returned to the widget, 0-1024 bytes
   * @return bool
   */
  public function answerWidgetQuery( $widget_query_id, array $options = [] )
  {
    return $this->apiCall( 'answerWidgetQuery', array_merge( ['widget_query_id' => $widget_query_id], $options ) );
  }

  /**
   * Use this method to reply to a received guest message. On success, a SentGuestMessage object is returned.
   * @see https://core.telegram.org/bots/api#answerguestquery
   *
   * @param string $guest_query_id Unique identifier for the query to be answered
   * @param array $result A JSON-serialized object describing the message to be sent
   * @return SentGuestMessage|false
   */
  public function answerGuestQuery( $guest_query_id, $result )
  {
    return $this->apiCall( 'answerGuestQuery', ['guest_query_id' => $guest_query_id, 'result' => $result], SentGuestMessage::class );
  }

  /**
   * Use this method to get the list of boosts added to a chat by a user. Requires administrator rights in the chat. Returns a UserChatBoosts object.
   * @see https://core.telegram.org/bots/api#getuserchatboosts
   *
   * @param int|string $chat_id Unique identifier for the chat or username of the channel in the format @username
   * @param int $user_id Unique identifier of the target user
   * @return UserChatBoosts|false
   */
  public function getUserChatBoosts( $chat_id, $user_id )
  {
    return $this->apiCall( 'getUserChatBoosts', ['chat_id' => $chat_id, 'user_id' => $user_id], UserChatBoosts::class );
  }

  /**
   * Use this method to get information about the connection of the bot with a business account. Returns a BusinessConnection object on success.
   * @see https://core.telegram.org/bots/api#getbusinessconnection
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @return BusinessConnection|false
   */
  public function getBusinessConnection( $business_connection_id )
  {
    return $this->apiCall( 'getBusinessConnection', ['business_connection_id' => $business_connection_id], BusinessConnection::class );
  }

  /**
   * Use this method to get the token of a managed bot. Returns the token as String on success.
   * @see https://core.telegram.org/bots/api#getmanagedbottoken
   *
   * @param int $user_id User identifier of the managed bot whose token will be returned
   * @return string|false
   */
  public function getManagedBotToken( $user_id )
  {
    return $this->apiCall( 'getManagedBotToken', ['user_id' => $user_id] );
  }

  /**
   * Use this method to revoke the current token of a managed bot and generate a new one. Returns the new token as String on success.
   * @see https://core.telegram.org/bots/api#replacemanagedbottoken
   *
   * @param int $user_id User identifier of the managed bot whose token will be replaced
   * @return string|false
   */
  public function replaceManagedBotToken( $user_id )
  {
    return $this->apiCall( 'replaceManagedBotToken', ['user_id' => $user_id] );
  }

  /**
   * Use this method to get the access settings of a managed bot. Returns a BotAccessSettings object on success.
   * @see https://core.telegram.org/bots/api#getmanagedbotaccesssettings
   *
   * @param int $user_id User identifier of the managed bot whose access settings will be returned
   * @return BotAccessSettings|false
   */
  public function getManagedBotAccessSettings( $user_id )
  {
    return $this->apiCall( 'getManagedBotAccessSettings', ['user_id' => $user_id], BotAccessSettings::class );
  }

  /**
   * Use this method to change the access settings of a managed bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmanagedbotaccesssettings
   *
   * @param int $user_id User identifier of the managed bot whose access settings will be changed
   * @param bool $is_access_restricted Pass True if only selected users can access the bot. The bot's owner can always access it.
   * @param array $options Optional parameters:
   *   - added_user_ids (Array of Integer) A JSON-serialized list of up to 10 identifiers of users who will have access to the bot in addition to its owner. Ignored if is_access_restricted is False.
   * @return bool
   */
  public function setManagedBotAccessSettings( $user_id, $is_access_restricted, array $options = [] )
  {
    return $this->apiCall( 'setManagedBotAccessSettings', array_merge( ['user_id' => $user_id, 'is_access_restricted' => $is_access_restricted], $options ) );
  }

  /**
   * Use this method to change the list of the bot's commands. See this manual for more details about bot commands. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmycommands
   *
   * @param array $commands A JSON-serialized list of bot commands to be set as the list of the bot's commands. At most 100 commands can be specified.
   * @param array $options Optional parameters:
   *   - scope (BotCommandScope) A JSON-serialized object, describing scope of users for which the commands are relevant. Defaults to BotCommandScopeDefault.
   *   - language_code (String) A two-letter ISO 639-1 language code. If empty, commands will be applied to all users from the given scope, for whose language there are no dedicated commands.
   * @return bool
   */
  public function setMyCommands( $commands, array $options = [] )
  {
    return $this->apiCall( 'setMyCommands', array_merge( ['commands' => $commands], $options ) );
  }

  /**
   * Use this method to delete the list of the bot's commands for the given scope and user language. After deletion, higher level commands will be shown to affected users. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletemycommands
   *
   * @param array $options Optional parameters:
   *   - scope (BotCommandScope) A JSON-serialized object, describing scope of users for which the commands are relevant. Defaults to BotCommandScopeDefault.
   *   - language_code (String) A two-letter ISO 639-1 language code. If empty, commands will be applied to all users from the given scope, for whose language there are no dedicated commands.
   * @return bool
   */
  public function deleteMyCommands( array $options = [] )
  {
    return $this->apiCall( 'deleteMyCommands', array_merge( [], $options ) );
  }

  /**
   * Use this method to get the current list of the bot's commands for the given scope and user language. Returns an Array of BotCommand objects. If commands aren't set, an empty list is returned.
   * @see https://core.telegram.org/bots/api#getmycommands
   *
   * @param array $options Optional parameters:
   *   - scope (BotCommandScope) A JSON-serialized object, describing scope of users. Defaults to BotCommandScopeDefault.
   *   - language_code (String) A two-letter ISO 639-1 language code or an empty string
   * @return BotCommand[]|false
   */
  public function getMyCommands( array $options = [] )
  {
    return $this->apiCall( 'getMyCommands', array_merge( [], $options ), BotCommand::class, true );
  }

  /**
   * Use this method to change the bot's name. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmyname
   *
   * @param array $options Optional parameters:
   *   - name (String) New bot name; 0-64 characters. Pass an empty string to remove the dedicated name for the given language.
   *   - language_code (String) A two-letter ISO 639-1 language code. If empty, the name will be shown to all users for whose language there is no dedicated name.
   * @return bool
   */
  public function setMyName( array $options = [] )
  {
    return $this->apiCall( 'setMyName', array_merge( [], $options ) );
  }

  /**
   * Use this method to get the current bot name for the given user language. Returns BotName on success.
   * @see https://core.telegram.org/bots/api#getmyname
   *
   * @param array $options Optional parameters:
   *   - language_code (String) A two-letter ISO 639-1 language code or an empty string
   * @return BotName|false
   */
  public function getMyName( array $options = [] )
  {
    return $this->apiCall( 'getMyName', array_merge( [], $options ), BotName::class );
  }

  /**
   * Use this method to change the bot's description, which is shown in the chat with the bot if the chat is empty. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmydescription
   *
   * @param array $options Optional parameters:
   *   - description (String) New bot description; 0-512 characters. Pass an empty string to remove the dedicated description for the given language.
   *   - language_code (String) A two-letter ISO 639-1 language code. If empty, the description will be applied to all users for whose language there is no dedicated description.
   * @return bool
   */
  public function setMyDescription( array $options = [] )
  {
    return $this->apiCall( 'setMyDescription', array_merge( [], $options ) );
  }

  /**
   * Use this method to get the current bot description for the given user language. Returns BotDescription on success.
   * @see https://core.telegram.org/bots/api#getmydescription
   *
   * @param array $options Optional parameters:
   *   - language_code (String) A two-letter ISO 639-1 language code or an empty string
   * @return BotDescription|false
   */
  public function getMyDescription( array $options = [] )
  {
    return $this->apiCall( 'getMyDescription', array_merge( [], $options ), BotDescription::class );
  }

  /**
   * Use this method to change the bot's short description, which is shown on the bot's profile page and is sent together with the link when users share the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmyshortdescription
   *
   * @param array $options Optional parameters:
   *   - short_description (String) New short description for the bot; 0-120 characters. Pass an empty string to remove the dedicated short description for the given language.
   *   - language_code (String) A two-letter ISO 639-1 language code. If empty, the short description will be applied to all users for whose language there is no dedicated short description.
   * @return bool
   */
  public function setMyShortDescription( array $options = [] )
  {
    return $this->apiCall( 'setMyShortDescription', array_merge( [], $options ) );
  }

  /**
   * Use this method to get the current bot short description for the given user language. Returns BotShortDescription on success.
   * @see https://core.telegram.org/bots/api#getmyshortdescription
   *
   * @param array $options Optional parameters:
   *   - language_code (String) A two-letter ISO 639-1 language code or an empty string
   * @return BotShortDescription|false
   */
  public function getMyShortDescription( array $options = [] )
  {
    return $this->apiCall( 'getMyShortDescription', array_merge( [], $options ), BotShortDescription::class );
  }

  /**
   * Changes the profile photo of the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmyprofilephoto
   *
   * @param array $photo The new profile photo to set
   * @return bool
   */
  public function setMyProfilePhoto( $photo )
  {
    return $this->apiCall( 'setMyProfilePhoto', ['photo' => $photo] );
  }

  /**
   * Removes the profile photo of the bot. Requires no parameters. Returns True on success.
   * @see https://core.telegram.org/bots/api#removemyprofilephoto
   *
   * @return bool
   */
  public function removeMyProfilePhoto()
  {
    return $this->apiCall( 'removeMyProfilePhoto', [] );
  }

  /**
   * Use this method to change the bot's menu button in a private chat, or the default menu button. Returns True on success.
   * @see https://core.telegram.org/bots/api#setchatmenubutton
   *
   * @param array $options Optional parameters:
   *   - chat_id (Integer) Unique identifier for the target private chat. If not specified, the bot's default menu button will be changed.
   *   - menu_button (MenuButton) A JSON-serialized object for the bot's new menu button. Defaults to MenuButtonDefault.
   * @return bool
   */
  public function setChatMenuButton( array $options = [] )
  {
    return $this->apiCall( 'setChatMenuButton', array_merge( [], $options ) );
  }

  /**
   * Use this method to get the current value of the bot's menu button in a private chat, or the default menu button. Returns MenuButton on success.
   * @see https://core.telegram.org/bots/api#getchatmenubutton
   *
   * @param array $options Optional parameters:
   *   - chat_id (Integer) Unique identifier for the target private chat. If not specified, the bot's default menu button will be returned.
   * @return MenuButton|false
   */
  public function getChatMenuButton( array $options = [] )
  {
    return $this->apiCall( 'getChatMenuButton', array_merge( [], $options ), MenuButton::class );
  }

  /**
   * Use this method to change the default administrator rights requested by the bot when it's added as an administrator to groups or channels. These rights will be suggested to users, but they are free to modify the list before adding the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setmydefaultadministratorrights
   *
   * @param array $options Optional parameters:
   *   - rights (ChatAdministratorRights) A JSON-serialized object describing new default administrator rights. If not specified, the default administrator rights will be cleared.
   *   - for_channels (Boolean) Pass True to change the default administrator rights of the bot in channels. Otherwise, the default administrator rights of the bot for groups and supergroups will be changed.
   * @return bool
   */
  public function setMyDefaultAdministratorRights( array $options = [] )
  {
    return $this->apiCall( 'setMyDefaultAdministratorRights', array_merge( [], $options ) );
  }

  /**
   * Use this method to get the current default administrator rights of the bot. Returns ChatAdministratorRights on success.
   * @see https://core.telegram.org/bots/api#getmydefaultadministratorrights
   *
   * @param array $options Optional parameters:
   *   - for_channels (Boolean) Pass True to get default administrator rights of the bot in channels. Otherwise, default administrator rights of the bot for groups and supergroups will be returned.
   * @return ChatAdministratorRights|false
   */
  public function getMyDefaultAdministratorRights( array $options = [] )
  {
    return $this->apiCall( 'getMyDefaultAdministratorRights', array_merge( [], $options ), ChatAdministratorRights::class );
  }

  /**
   * Returns the list of gifts that can be sent by the bot to users and channel chats. Requires no parameters. Returns a Gifts object.
   * @see https://core.telegram.org/bots/api#getavailablegifts
   *
   * @return Gifts|false
   */
  public function getAvailableGifts()
  {
    return $this->apiCall( 'getAvailableGifts', [], Gifts::class );
  }

  /**
   * Sends a gift to the given user or channel chat. The gift can't be converted to Telegram Stars by the receiver. Returns True on success.
   * @see https://core.telegram.org/bots/api#sendgift
   *
   * @param string $gift_id Identifier of the gift; limited gifts can't be sent to channel chats
   * @param array $options Optional parameters:
   *   - user_id (Integer) Required if chat_id is not specified. Unique identifier of the target user who will receive the gift.
   *   - chat_id (Integer or String) Required if user_id is not specified. Unique identifier for the chat that will receive the gift or username of the channel in the format @username.
   *   - pay_for_upgrade (Boolean) Pass True to pay for the gift upgrade from the bot's balance, thereby making the upgrade free for the receiver
   *   - text (String) Text that will be shown along with the gift; 0-128 characters
   *   - text_parse_mode (String) Mode for parsing entities in the text. See formatting options for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
   *   - text_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of text_parse_mode. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
   * @return bool
   */
  public function sendGift( $gift_id, array $options = [] )
  {
    return $this->apiCall( 'sendGift', array_merge( ['gift_id' => $gift_id], $options ) );
  }

  /**
   * Gifts a Telegram Premium subscription to the given user. Returns True on success.
   * @see https://core.telegram.org/bots/api#giftpremiumsubscription
   *
   * @param int $user_id Unique identifier of the target user who will receive a Telegram Premium subscription
   * @param int $month_count Number of months the Telegram Premium subscription will be active for the user; must be one of 3, 6, or 12
   * @param int $star_count Number of Telegram Stars to pay for the Telegram Premium subscription; must be 1000 for 3 months, 1500 for 6 months, and 2500 for 12 months
   * @param array $options Optional parameters:
   *   - text (String) Text that will be shown along with the service message about the subscription; 0-128 characters
   *   - text_parse_mode (String) Mode for parsing entities in the text. See formatting options for more details. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
   *   - text_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the gift text. It can be specified instead of text_parse_mode. Entities other than “bold”, “italic”, “underline”, “strikethrough”, “spoiler”, “custom_emoji”, and “date_time” are ignored.
   * @return bool
   */
  public function giftPremiumSubscription( $user_id, $month_count, $star_count, array $options = [] )
  {
    return $this->apiCall( 'giftPremiumSubscription', array_merge( ['user_id' => $user_id, 'month_count' => $month_count, 'star_count' => $star_count], $options ) );
  }

  /**
   * Verifies a user on behalf of the organization which is represented by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#verifyuser
   *
   * @param int $user_id Unique identifier of the target user
   * @param array $options Optional parameters:
   *   - custom_description (String) Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
   *   - custom_description_parse_mode (String) Mode for parsing entities in the custom description. See formatting options for more details. Only “text_link” entities are supported.
   *   - custom_description_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the custom description, which can be specified instead of custom_description_parse_mode. Only “text_link” entities are supported.
   * @return bool
   */
  public function verifyUser( $user_id, array $options = [] )
  {
    return $this->apiCall( 'verifyUser', array_merge( ['user_id' => $user_id], $options ) );
  }

  /**
   * Verifies a chat on behalf of the organization which is represented by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#verifychat
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username. Channel direct messages chats can't be verified.
   * @param array $options Optional parameters:
   *   - custom_description (String) Custom description for the verification; 0-70 characters. Must be empty if the organization isn't allowed to provide a custom verification description.
   *   - custom_description_parse_mode (String) Mode for parsing entities in the custom description. See formatting options for more details. Only “text_link” entities are supported.
   *   - custom_description_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the custom description, which can be specified instead of custom_description_parse_mode. Only “text_link” entities are supported.
   * @return bool
   */
  public function verifyChat( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'verifyChat', array_merge( ['chat_id' => $chat_id], $options ) );
  }

  /**
   * Removes verification from a user who is currently verified on behalf of the organization represented by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#removeuserverification
   *
   * @param int $user_id Unique identifier of the target user
   * @return bool
   */
  public function removeUserVerification( $user_id )
  {
    return $this->apiCall( 'removeUserVerification', ['user_id' => $user_id] );
  }

  /**
   * Removes verification from a chat that is currently verified on behalf of the organization represented by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#removechatverification
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot or channel in the format @username
   * @return bool
   */
  public function removeChatVerification( $chat_id )
  {
    return $this->apiCall( 'removeChatVerification', ['chat_id' => $chat_id] );
  }

  /**
   * Marks incoming message as read on behalf of a business account. Requires the can_read_messages business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#readbusinessmessage
   *
   * @param string $business_connection_id Unique identifier of the business connection on behalf of which to read the message
   * @param int $chat_id Unique identifier of the chat in which the message was received. The chat must have been active in the last 24 hours.
   * @param int $message_id Unique identifier of the message to mark as read
   * @return bool
   */
  public function readBusinessMessage( $business_connection_id, $chat_id, $message_id )
  {
    return $this->apiCall( 'readBusinessMessage', ['business_connection_id' => $business_connection_id, 'chat_id' => $chat_id, 'message_id' => $message_id] );
  }

  /**
   * Delete messages on behalf of a business account. Requires the can_delete_sent_messages business bot right to delete messages sent by the bot itself, or the can_delete_all_messages business bot right to delete any message. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletebusinessmessages
   *
   * @param string $business_connection_id Unique identifier of the business connection on behalf of which to delete the messages
   * @param array $message_ids A JSON-serialized list of 1-100 identifiers of messages to delete. All messages must be from the same chat. See deleteMessage for limitations on which messages can be deleted.
   * @return bool
   */
  public function deleteBusinessMessages( $business_connection_id, $message_ids )
  {
    return $this->apiCall( 'deleteBusinessMessages', ['business_connection_id' => $business_connection_id, 'message_ids' => $message_ids] );
  }

  /**
   * Changes the first and last name of a managed business account. Requires the can_change_name business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#setbusinessaccountname
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param string $first_name The new value of the first name for the business account; 1-64 characters
   * @param array $options Optional parameters:
   *   - last_name (String) The new value of the last name for the business account; 0-64 characters
   * @return bool
   */
  public function setBusinessAccountName( $business_connection_id, $first_name, array $options = [] )
  {
    return $this->apiCall( 'setBusinessAccountName', array_merge( ['business_connection_id' => $business_connection_id, 'first_name' => $first_name], $options ) );
  }

  /**
   * Changes the username of a managed business account. Requires the can_change_username business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#setbusinessaccountusername
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param array $options Optional parameters:
   *   - username (String) The new value of the username for the business account; 0-32 characters
   * @return bool
   */
  public function setBusinessAccountUsername( $business_connection_id, array $options = [] )
  {
    return $this->apiCall( 'setBusinessAccountUsername', array_merge( ['business_connection_id' => $business_connection_id], $options ) );
  }

  /**
   * Changes the bio of a managed business account. Requires the can_change_bio business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#setbusinessaccountbio
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param array $options Optional parameters:
   *   - bio (String) The new value of the bio for the business account; 0-140 characters
   * @return bool
   */
  public function setBusinessAccountBio( $business_connection_id, array $options = [] )
  {
    return $this->apiCall( 'setBusinessAccountBio', array_merge( ['business_connection_id' => $business_connection_id], $options ) );
  }

  /**
   * Changes the profile photo of a managed business account. Requires the can_edit_profile_photo business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#setbusinessaccountprofilephoto
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param array $photo The new profile photo to set
   * @param array $options Optional parameters:
   *   - is_public (Boolean) Pass True to set the public photo, which will be visible even if the main photo is hidden by the business account's privacy settings. An account can have only one public photo.
   * @return bool
   */
  public function setBusinessAccountProfilePhoto( $business_connection_id, $photo, array $options = [] )
  {
    return $this->apiCall( 'setBusinessAccountProfilePhoto', array_merge( ['business_connection_id' => $business_connection_id, 'photo' => $photo], $options ) );
  }

  /**
   * Removes the current profile photo of a managed business account. Requires the can_edit_profile_photo business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#removebusinessaccountprofilephoto
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param array $options Optional parameters:
   *   - is_public (Boolean) Pass True to remove the public photo, which is visible even if the main photo is hidden by the business account's privacy settings. After the main photo is removed, the previous profile photo (if present) becomes the main photo.
   * @return bool
   */
  public function removeBusinessAccountProfilePhoto( $business_connection_id, array $options = [] )
  {
    return $this->apiCall( 'removeBusinessAccountProfilePhoto', array_merge( ['business_connection_id' => $business_connection_id], $options ) );
  }

  /**
   * Changes the privacy settings pertaining to incoming gifts in a managed business account. Requires the can_change_gift_settings business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#setbusinessaccountgiftsettings
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param bool $show_gift_button Pass True if a button for sending a gift to the user or by the business account must always be shown in the input field
   * @param array $accepted_gift_types Types of gifts accepted by the business account
   * @return bool
   */
  public function setBusinessAccountGiftSettings( $business_connection_id, $show_gift_button, $accepted_gift_types )
  {
    return $this->apiCall( 'setBusinessAccountGiftSettings', ['business_connection_id' => $business_connection_id, 'show_gift_button' => $show_gift_button, 'accepted_gift_types' => $accepted_gift_types] );
  }

  /**
   * Returns the amount of Telegram Stars owned by a managed business account. Requires the can_view_gifts_and_stars business bot right. Returns StarAmount on success.
   * @see https://core.telegram.org/bots/api#getbusinessaccountstarbalance
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @return StarAmount|false
   */
  public function getBusinessAccountStarBalance( $business_connection_id )
  {
    return $this->apiCall( 'getBusinessAccountStarBalance', ['business_connection_id' => $business_connection_id], StarAmount::class );
  }

  /**
   * Transfers Telegram Stars from the business account balance to the bot's balance. Requires the can_transfer_stars business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#transferbusinessaccountstars
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param int $star_count Number of Telegram Stars to transfer; 1-10000
   * @return bool
   */
  public function transferBusinessAccountStars( $business_connection_id, $star_count )
  {
    return $this->apiCall( 'transferBusinessAccountStars', ['business_connection_id' => $business_connection_id, 'star_count' => $star_count] );
  }

  /**
   * Returns the gifts received and owned by a managed business account. Requires the can_view_gifts_and_stars business bot right. Returns OwnedGifts on success.
   * @see https://core.telegram.org/bots/api#getbusinessaccountgifts
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param array $options Optional parameters:
   *   - exclude_unsaved (Boolean) Pass True to exclude gifts that aren't saved to the account's profile page
   *   - exclude_saved (Boolean) Pass True to exclude gifts that are saved to the account's profile page
   *   - exclude_unlimited (Boolean) Pass True to exclude gifts that can be purchased an unlimited number of times
   *   - exclude_limited_upgradable (Boolean) Pass True to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
   *   - exclude_limited_non_upgradable (Boolean) Pass True to exclude gifts that can be purchased a limited number of times and can't be upgraded to unique
   *   - exclude_unique (Boolean) Pass True to exclude unique gifts
   *   - exclude_from_blockchain (Boolean) Pass True to exclude gifts that were assigned from the TON blockchain and can't be resold or transferred in Telegram
   *   - sort_by_price (Boolean) Pass True to sort results by gift price instead of send date. Sorting is applied before pagination.
   *   - offset (String) Offset of the first entry to return as received from the previous request; use empty string to get the first chunk of results
   *   - limit (Integer) The maximum number of gifts to be returned; 1-100. Defaults to 100.
   * @return OwnedGifts|false
   */
  public function getBusinessAccountGifts( $business_connection_id, array $options = [] )
  {
    return $this->apiCall( 'getBusinessAccountGifts', array_merge( ['business_connection_id' => $business_connection_id], $options ), OwnedGifts::class );
  }

  /**
   * Returns the gifts owned and hosted by a user. Returns OwnedGifts on success.
   * @see https://core.telegram.org/bots/api#getusergifts
   *
   * @param int $user_id Unique identifier of the user
   * @param array $options Optional parameters:
   *   - exclude_unlimited (Boolean) Pass True to exclude gifts that can be purchased an unlimited number of times
   *   - exclude_limited_upgradable (Boolean) Pass True to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
   *   - exclude_limited_non_upgradable (Boolean) Pass True to exclude gifts that can be purchased a limited number of times and can't be upgraded to unique
   *   - exclude_from_blockchain (Boolean) Pass True to exclude gifts that were assigned from the TON blockchain and can't be resold or transferred in Telegram
   *   - exclude_unique (Boolean) Pass True to exclude unique gifts
   *   - sort_by_price (Boolean) Pass True to sort results by gift price instead of send date. Sorting is applied before pagination.
   *   - offset (String) Offset of the first entry to return as received from the previous request; use an empty string to get the first chunk of results
   *   - limit (Integer) The maximum number of gifts to be returned; 1-100. Defaults to 100.
   * @return OwnedGifts|false
   */
  public function getUserGifts( $user_id, array $options = [] )
  {
    return $this->apiCall( 'getUserGifts', array_merge( ['user_id' => $user_id], $options ), OwnedGifts::class );
  }

  /**
   * Returns the gifts owned by a chat. Returns OwnedGifts on success.
   * @see https://core.telegram.org/bots/api#getchatgifts
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target channel in the format @username
   * @param array $options Optional parameters:
   *   - exclude_unsaved (Boolean) Pass True to exclude gifts that aren't saved to the chat's profile page. Always True, unless the bot has the can_post_messages administrator right in the channel.
   *   - exclude_saved (Boolean) Pass True to exclude gifts that are saved to the chat's profile page. Always False, unless the bot has the can_post_messages administrator right in the channel.
   *   - exclude_unlimited (Boolean) Pass True to exclude gifts that can be purchased an unlimited number of times
   *   - exclude_limited_upgradable (Boolean) Pass True to exclude gifts that can be purchased a limited number of times and can be upgraded to unique
   *   - exclude_limited_non_upgradable (Boolean) Pass True to exclude gifts that can be purchased a limited number of times and can't be upgraded to unique
   *   - exclude_from_blockchain (Boolean) Pass True to exclude gifts that were assigned from the TON blockchain and can't be resold or transferred in Telegram
   *   - exclude_unique (Boolean) Pass True to exclude unique gifts
   *   - sort_by_price (Boolean) Pass True to sort results by gift price instead of send date. Sorting is applied before pagination.
   *   - offset (String) Offset of the first entry to return as received from the previous request; use an empty string to get the first chunk of results
   *   - limit (Integer) The maximum number of gifts to be returned; 1-100. Defaults to 100.
   * @return OwnedGifts|false
   */
  public function getChatGifts( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'getChatGifts', array_merge( ['chat_id' => $chat_id], $options ), OwnedGifts::class );
  }

  /**
   * Use this method to get information about a unique gift by its name. Returns a UniqueGift object on success.
   * @see https://core.telegram.org/bots/api#getuniquegift
   *
   * @param string $name Unique name of the gift
   * @return UniqueGift|false
   */
  public function getUniqueGift( $name )
  {
    return $this->apiCall( 'getUniqueGift', ['name' => $name], UniqueGift::class );
  }

  /**
   * Converts a given regular gift to Telegram Stars. Requires the can_convert_gifts_to_stars business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#convertgifttostars
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param string $owned_gift_id Unique identifier of the regular gift that should be converted to Telegram Stars
   * @return bool
   */
  public function convertGiftToStars( $business_connection_id, $owned_gift_id )
  {
    return $this->apiCall( 'convertGiftToStars', ['business_connection_id' => $business_connection_id, 'owned_gift_id' => $owned_gift_id] );
  }

  /**
   * Upgrades a given regular gift to a unique gift. Requires the can_transfer_and_upgrade_gifts business bot right. Additionally requires the can_transfer_stars business bot right if the upgrade is paid. Returns True on success.
   * @see https://core.telegram.org/bots/api#upgradegift
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param string $owned_gift_id Unique identifier of the regular gift that should be upgraded to a unique one
   * @param array $options Optional parameters:
   *   - keep_original_details (Boolean) Pass True to keep the original gift text, sender and receiver in the upgraded gift
   *   - star_count (Integer) The amount of Telegram Stars that will be paid for the upgrade from the business account balance. If gift.prepaid_upgrade_star_count > 0, then pass 0, otherwise, the can_transfer_stars business bot right is required and gift.upgrade_star_count must be passed.
   * @return bool
   */
  public function upgradeGift( $business_connection_id, $owned_gift_id, array $options = [] )
  {
    return $this->apiCall( 'upgradeGift', array_merge( ['business_connection_id' => $business_connection_id, 'owned_gift_id' => $owned_gift_id], $options ) );
  }

  /**
   * Transfers an owned unique gift to another user. Requires the can_transfer_and_upgrade_gifts business bot right. Requires can_transfer_stars business bot right if the transfer is paid. Returns True on success.
   * @see https://core.telegram.org/bots/api#transfergift
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param string $owned_gift_id Unique identifier of the regular gift that should be transferred
   * @param int $new_owner_chat_id Unique identifier of the chat which will own the gift. The chat must be active in the last 24 hours.
   * @param array $options Optional parameters:
   *   - star_count (Integer) The amount of Telegram Stars that will be paid for the transfer from the business account balance. If positive, then the can_transfer_stars business bot right is required.
   * @return bool
   */
  public function transferGift( $business_connection_id, $owned_gift_id, $new_owner_chat_id, array $options = [] )
  {
    return $this->apiCall( 'transferGift', array_merge( ['business_connection_id' => $business_connection_id, 'owned_gift_id' => $owned_gift_id, 'new_owner_chat_id' => $new_owner_chat_id], $options ) );
  }

  /**
   * Deletes a regular gift owned by a managed business account without receiving Telegram Stars for it. Requires the can_convert_gifts_to_stars business bot right. The gift can be deleted only if it isn't limited (the field total_count of the Gift is omitted) and wasn't refunded. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletebusinessaccountgift
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param string $owned_gift_id Unique identifier of the regular gift that should be deleted
   * @return bool
   */
  public function deleteBusinessAccountGift( $business_connection_id, $owned_gift_id )
  {
    return $this->apiCall( 'deleteBusinessAccountGift', ['business_connection_id' => $business_connection_id, 'owned_gift_id' => $owned_gift_id] );
  }

  /**
   * Posts a story on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns Story on success.
   * @see https://core.telegram.org/bots/api#poststory
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param array $content Content of the story
   * @param int $active_period Period after which the story is moved to the archive, in seconds; must be one of 6 * 3600, 12 * 3600, 86400, or 2 * 86400
   * @param array $options Optional parameters:
   *   - caption (String) Caption of the story, 0-2048 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the story caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - areas (Array of StoryArea) A JSON-serialized list of clickable areas to be shown on the story
   *   - post_to_chat_page (Boolean) Pass True to keep the story accessible after it expires
   *   - protect_content (Boolean) Pass True if the content of the story must be protected from forwarding and screenshotting
   * @return Story|false
   */
  public function postStory( $business_connection_id, $content, $active_period, array $options = [] )
  {
    return $this->apiCall( 'postStory', array_merge( ['business_connection_id' => $business_connection_id, 'content' => $content, 'active_period' => $active_period], $options ), Story::class );
  }

  /**
   * Reposts a story on behalf of a business account from another business account. Both business accounts must be managed by the same bot, and the story on the source account must have been posted (or reposted) by the bot. Requires the can_manage_stories business bot right for both business accounts. Returns Story on success.
   * @see https://core.telegram.org/bots/api#repoststory
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param int $from_chat_id Unique identifier of the chat which posted the story that should be reposted
   * @param int $from_story_id Unique identifier of the story that should be reposted
   * @param int $active_period Period after which the story is moved to the archive, in seconds; must be one of 6 * 3600, 12 * 3600, 86400, or 2 * 86400
   * @param array $options Optional parameters:
   *   - post_to_chat_page (Boolean) Pass True to keep the story accessible after it expires
   *   - protect_content (Boolean) Pass True if the content of the story must be protected from forwarding and screenshotting
   * @return Story|false
   */
  public function repostStory( $business_connection_id, $from_chat_id, $from_story_id, $active_period, array $options = [] )
  {
    return $this->apiCall( 'repostStory', array_merge( ['business_connection_id' => $business_connection_id, 'from_chat_id' => $from_chat_id, 'from_story_id' => $from_story_id, 'active_period' => $active_period], $options ), Story::class );
  }

  /**
   * Edits a story previously posted by the bot on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns Story on success.
   * @see https://core.telegram.org/bots/api#editstory
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param int $story_id Unique identifier of the story to edit
   * @param array $content Content of the story
   * @param array $options Optional parameters:
   *   - caption (String) Caption of the story, 0-2048 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the story caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - areas (Array of StoryArea) A JSON-serialized list of clickable areas to be shown on the story
   * @return Story|false
   */
  public function editStory( $business_connection_id, $story_id, $content, array $options = [] )
  {
    return $this->apiCall( 'editStory', array_merge( ['business_connection_id' => $business_connection_id, 'story_id' => $story_id, 'content' => $content], $options ), Story::class );
  }

  /**
   * Deletes a story previously posted by the bot on behalf of a managed business account. Requires the can_manage_stories business bot right. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletestory
   *
   * @param string $business_connection_id Unique identifier of the business connection
   * @param int $story_id Unique identifier of the story to delete
   * @return bool
   */
  public function deleteStory( $business_connection_id, $story_id )
  {
    return $this->apiCall( 'deleteStory', ['business_connection_id' => $business_connection_id, 'story_id' => $story_id] );
  }

  /**
   * Use this method to set the result of an interaction with a Web App and send a corresponding message on behalf of the user to the chat from which the query originated. On success, a SentWebAppMessage object is returned.
   * @see https://core.telegram.org/bots/api#answerwebappquery
   *
   * @param string $web_app_query_id Unique identifier for the query to be answered
   * @param array $result A JSON-serialized object describing the message to be sent
   * @return SentWebAppMessage|false
   */
  public function answerWebAppQuery( $web_app_query_id, $result )
  {
    return $this->apiCall( 'answerWebAppQuery', ['web_app_query_id' => $web_app_query_id, 'result' => $result], SentWebAppMessage::class );
  }

  /**
   * Stores a message that can be sent by a user of a Mini App. Returns a PreparedInlineMessage object.
   * @see https://core.telegram.org/bots/api#savepreparedinlinemessage
   *
   * @param int $user_id Unique identifier of the target user that can use the prepared message
   * @param array $result A JSON-serialized object describing the message to be sent
   * @param array $options Optional parameters:
   *   - allow_user_chats (Boolean) Pass True if the message can be sent to private chats with users
   *   - allow_bot_chats (Boolean) Pass True if the message can be sent to private chats with bots
   *   - allow_group_chats (Boolean) Pass True if the message can be sent to group and supergroup chats
   *   - allow_channel_chats (Boolean) Pass True if the message can be sent to channel chats
   * @return PreparedInlineMessage|false
   */
  public function savePreparedInlineMessage( $user_id, $result, array $options = [] )
  {
    return $this->apiCall( 'savePreparedInlineMessage', array_merge( ['user_id' => $user_id, 'result' => $result], $options ), PreparedInlineMessage::class );
  }

  /**
   * Stores a keyboard button that can be used by a user within a Mini App. Returns a PreparedKeyboardButton object.
   * @see https://core.telegram.org/bots/api#savepreparedkeyboardbutton
   *
   * @param int $user_id Unique identifier of the target user that can use the button
   * @param array $button A JSON-serialized object describing the button to be saved. The button must be of the type request_users, request_chat, or request_managed_bot.
   * @return PreparedKeyboardButton|false
   */
  public function savePreparedKeyboardButton( $user_id, $button )
  {
    return $this->apiCall( 'savePreparedKeyboardButton', ['user_id' => $user_id, 'button' => $button], PreparedKeyboardButton::class );
  }

  /**
   * Use this method to edit text, rich and game messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within 48 hours from the time they were sent.
   * @see https://core.telegram.org/bots/api#editmessagetext
   *
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message to edit.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - text (String) New text of the message, 1-4096 characters after entity parsing; required if rich_message isn't specified
   *   - parse_mode (String) Mode for parsing entities in the message text. See formatting options for more details.
   *   - entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
   *   - link_preview_options (LinkPreviewOptions) Link preview generation options for the message
   *   - rich_message (InputRichMessage) New rich content of the message; required if text isn't specified. Direct upload of new files and explicit upload of files by a URL isn't supported when an inline message is edited.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return Message|bool
   */
  public function editMessageText( array $options = [] )
  {
    return $this->apiCall( 'editMessageText', array_merge( [], $options ), Message::class );
  }

  /**
   * Use this method to edit captions of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within 48 hours from the time they were sent.
   * @see https://core.telegram.org/bots/api#editmessagecaption
   *
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message to edit.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - caption (String) New caption of the message, 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the message caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media. Supported only for animation, photo and video messages.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return Message|bool
   */
  public function editMessageCaption( array $options = [] )
  {
    return $this->apiCall( 'editMessageCaption', array_merge( [], $options ), Message::class );
  }

  /**
   * Use this method to edit animation, audio, document, live photo, photo, or video messages, or to replace a text or a rich message with a media. If a message is part of a message album, then it can be edited only to an audio for audio albums, only to a document for document albums and to a photo, a live photo, or a video otherwise. When an inline message is edited, a new file can't be uploaded; use a previously uploaded file via its file_id or specify a URL. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within 48 hours from the time they were sent.
   * @see https://core.telegram.org/bots/api#editmessagemedia
   *
   * @param array $media A JSON-serialized object for the new media content of the message
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message to edit.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for a new inline keyboard
   * @return Message|bool
   */
  public function editMessageMedia( $media, array $options = [] )
  {
    return $this->apiCall( 'editMessageMedia', array_merge( ['media' => $media], $options ), Message::class );
  }

  /**
   * Use this method to edit live location messages. A location can be edited until its live_period expires or editing is explicitly disabled by a call to stopMessageLiveLocation. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned.
   * @see https://core.telegram.org/bots/api#editmessagelivelocation
   *
   * @param float $latitude Latitude of new location
   * @param float $longitude Longitude of new location
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message to edit.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - live_period (Integer) New period in seconds during which the location can be updated, starting from the message send date. If 0x7FFFFFFF is specified, then the location can be updated forever. Otherwise, the new value must not exceed the current live_period by more than a day, and the live location expiration date must remain within the next 90 days. If not specified, then live_period remains unchanged.
   *   - horizontal_accuracy (Float) The radius of uncertainty for the location, measured in meters; 0-1500
   *   - heading (Integer) Direction in which the user is moving, in degrees. Must be between 1 and 360 if specified.
   *   - proximity_alert_radius (Integer) The maximum distance for proximity alerts about approaching another chat member, in meters. Must be between 1 and 100000 if specified.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for a new inline keyboard
   * @return Message|bool
   */
  public function editMessageLiveLocation( $latitude, $longitude, array $options = [] )
  {
    return $this->apiCall( 'editMessageLiveLocation', array_merge( ['latitude' => $latitude, 'longitude' => $longitude], $options ), Message::class );
  }

  /**
   * Use this method to stop updating a live location message before live_period expires. On success, if the message is not an inline message, the edited Message is returned, otherwise True is returned.
   * @see https://core.telegram.org/bots/api#stopmessagelivelocation
   *
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message with live location to stop.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for a new inline keyboard
   * @return Message|bool
   */
  public function stopMessageLiveLocation( array $options = [] )
  {
    return $this->apiCall( 'stopMessageLiveLocation', array_merge( [], $options ), Message::class );
  }

  /**
   * Use this method to edit a checklist on behalf of a connected business account. On success, the edited Message is returned.
   * @see https://core.telegram.org/bots/api#editmessagechecklist
   *
   * @param string $business_connection_id Unique identifier of the business connection on behalf of which the message will be sent
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot in the format @username
   * @param int $message_id Unique identifier for the target message
   * @param array $checklist A JSON-serialized object for the new checklist
   * @param array $options Optional parameters:
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for the new inline keyboard for the message
   * @return Message|false
   */
  public function editMessageChecklist( $business_connection_id, $chat_id, $message_id, $checklist, array $options = [] )
  {
    return $this->apiCall( 'editMessageChecklist', array_merge( ['business_connection_id' => $business_connection_id, 'chat_id' => $chat_id, 'message_id' => $message_id, 'checklist' => $checklist], $options ), Message::class );
  }

  /**
   * Use this method to edit data of a widget in a message sent by the bot or via the bot (for inline bots). Public data of a shared widget can be changed at most once per second. On success, True is returned.
   * @see https://core.telegram.org/bots/api#editmessagewidgetdata
   *
   * @param array $options Optional parameters:
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target supergroup in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message with the widget.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - public_data (String) New public data of the widget, 0-1024 bytes; pass an empty string to remove the public data. If omitted, then the public data isn't changed.
   *   - chat_data (String) New data of the widget, which is specific to the message, 0-1024 bytes; pass an empty string to remove the data. If omitted, then the data isn't changed.
   * @return bool
   */
  public function editMessageWidgetData( array $options = [] )
  {
    return $this->apiCall( 'editMessageWidgetData', array_merge( [], $options ) );
  }

  /**
   * Use this method to edit public data of a shared widget created by the bot. A shared widget can be edited at most once per second. On success, True is returned.
   * @see https://core.telegram.org/bots/api#editsharedwidgetdata
   *
   * @param string $widget_id Unique identifier of the shared widget
   * @param string $public_data New public data of the widget, 0-1024 bytes; pass an empty string to remove the public data
   * @param array $options Optional parameters:
   *   - cache_time (Integer) The maximum amount of time in seconds that the public data of the widget may be cached client-side; 5-3600. If omitted, then the current value is kept, which is 30 by default.
   * @return bool
   */
  public function editSharedWidgetData( $widget_id, $public_data, array $options = [] )
  {
    return $this->apiCall( 'editSharedWidgetData', array_merge( ['widget_id' => $widget_id, 'public_data' => $public_data], $options ) );
  }

  /**
   * Use this method to edit only the reply markup of messages. On success, if the edited message is not an inline message, the edited Message is returned, otherwise True is returned. Note that business messages that were not sent by the bot and do not contain an inline keyboard can only be edited within 48 hours from the time they were sent.
   * @see https://core.telegram.org/bots/api#editmessagereplymarkup
   *
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - chat_id (Integer or String) Required if inline_message_id is not specified. Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the message to edit.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return Message|bool
   */
  public function editMessageReplyMarkup( array $options = [] )
  {
    return $this->apiCall( 'editMessageReplyMarkup', array_merge( [], $options ), Message::class );
  }

  /**
   * Use this method to stop a poll which was sent by the bot. On success, the stopped Poll is returned.
   * @see https://core.telegram.org/bots/api#stoppoll
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param int $message_id Identifier of the original message with the poll
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message to be edited was sent
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for a new message inline keyboard
   * @return Poll|false
   */
  public function stopPoll( $chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'stopPoll', array_merge( ['chat_id' => $chat_id, 'message_id' => $message_id], $options ), Poll::class );
  }

  /**
   * Use this method to edit an ephemeral text or rich message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
   * @see https://core.telegram.org/bots/api#editephemeralmessagetext
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $receiver_user_id Identifier of the user who received the message
   * @param int $ephemeral_message_id Identifier of the ephemeral message to edit
   * @param array $options Optional parameters:
   *   - text (String) New text of the message, 1-4096 characters after entity parsing; required if rich_message isn't specified
   *   - parse_mode (String) Mode for parsing entities in the message text. See formatting options for more details.
   *   - entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in message text, which can be specified instead of parse_mode
   *   - rich_message (InputRichMessage) New rich content of the message; required if text isn't specified
   *   - link_preview_options (LinkPreviewOptions) Link preview generation options for the message
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return bool
   */
  public function editEphemeralMessageText( $chat_id, $receiver_user_id, $ephemeral_message_id, array $options = [] )
  {
    return $this->apiCall( 'editEphemeralMessageText', array_merge( ['chat_id' => $chat_id, 'receiver_user_id' => $receiver_user_id, 'ephemeral_message_id' => $ephemeral_message_id], $options ) );
  }

  /**
   * Use this method to edit the media of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
   * @see https://core.telegram.org/bots/api#editephemeralmessagemedia
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $receiver_user_id Identifier of the user who received the message
   * @param int $ephemeral_message_id Identifier of the ephemeral message to edit
   * @param array $media A JSON-serialized object for the new media content of the message
   * @param array $options Optional parameters:
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return bool
   */
  public function editEphemeralMessageMedia( $chat_id, $receiver_user_id, $ephemeral_message_id, $media, array $options = [] )
  {
    return $this->apiCall( 'editEphemeralMessageMedia', array_merge( ['chat_id' => $chat_id, 'receiver_user_id' => $receiver_user_id, 'ephemeral_message_id' => $ephemeral_message_id, 'media' => $media], $options ) );
  }

  /**
   * Use this method to edit the caption of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
   * @see https://core.telegram.org/bots/api#editephemeralmessagecaption
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $receiver_user_id Identifier of the user who received the message
   * @param int $ephemeral_message_id Identifier of the ephemeral message to edit
   * @param array $options Optional parameters:
   *   - caption (String) New caption of the message, 0-1024 characters after entities parsing
   *   - parse_mode (String) Mode for parsing entities in the message caption. See formatting options for more details.
   *   - caption_entities (Array of MessageEntity) A JSON-serialized list of special entities that appear in the caption, which can be specified instead of parse_mode
   *   - show_caption_above_media (Boolean) Pass True if the caption must be shown above the message media. Supported only for animation, photo and video messages.
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return bool
   */
  public function editEphemeralMessageCaption( $chat_id, $receiver_user_id, $ephemeral_message_id, array $options = [] )
  {
    return $this->apiCall( 'editEphemeralMessageCaption', array_merge( ['chat_id' => $chat_id, 'receiver_user_id' => $receiver_user_id, 'ephemeral_message_id' => $ephemeral_message_id], $options ) );
  }

  /**
   * Use this method to edit only the reply markup of an ephemeral message. Note that it is not guaranteed that the user will receive the message edit event, especially if they are offline. On success, True is returned.
   * @see https://core.telegram.org/bots/api#editephemeralmessagereplymarkup
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $receiver_user_id Identifier of the user who received the message
   * @param int $ephemeral_message_id Identifier of the ephemeral message to edit
   * @param array $options Optional parameters:
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard
   * @return bool
   */
  public function editEphemeralMessageReplyMarkup( $chat_id, $receiver_user_id, $ephemeral_message_id, array $options = [] )
  {
    return $this->apiCall( 'editEphemeralMessageReplyMarkup', array_merge( ['chat_id' => $chat_id, 'receiver_user_id' => $receiver_user_id, 'ephemeral_message_id' => $ephemeral_message_id], $options ) );
  }

  /**
   * Use this method to approve a suggested post in a direct messages chat. The bot must have the 'can_post_messages' administrator right in the corresponding channel chat. Returns True on success.
   * @see https://core.telegram.org/bots/api#approvesuggestedpost
   *
   * @param int $chat_id Unique identifier for the target direct messages chat
   * @param int $message_id Identifier of a suggested post message to approve
   * @param array $options Optional parameters:
   *   - send_date (Integer) Point in time (Unix timestamp) when the post is expected to be published; omit if the date has already been specified when the suggested post was created. If specified, then the date must be not more than 2678400 seconds (30 days) in the future.
   * @return bool
   */
  public function approveSuggestedPost( $chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'approveSuggestedPost', array_merge( ['chat_id' => $chat_id, 'message_id' => $message_id], $options ) );
  }

  /**
   * Use this method to decline a suggested post in a direct messages chat. The bot must have the 'can_manage_direct_messages' administrator right in the corresponding channel chat. Returns True on success.
   * @see https://core.telegram.org/bots/api#declinesuggestedpost
   *
   * @param int $chat_id Unique identifier for the target direct messages chat
   * @param int $message_id Identifier of a suggested post message to decline
   * @param array $options Optional parameters:
   *   - comment (String) Comment for the creator of the suggested post; 0-128 characters
   * @return bool
   */
  public function declineSuggestedPost( $chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'declineSuggestedPost', array_merge( ['chat_id' => $chat_id, 'message_id' => $message_id], $options ) );
  }

  /**
   * Use this method to delete multiple messages simultaneously. If some of the specified messages can't be found, they are skipped. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletemessages
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username
   * @param array $message_ids A JSON-serialized list of 1-100 identifiers of messages to delete. See deleteMessage for limitations on which messages can be deleted.
   * @return bool
   */
  public function deleteMessages( $chat_id, $message_ids )
  {
    return $this->apiCall( 'deleteMessages', ['chat_id' => $chat_id, 'message_ids' => $message_ids] );
  }

  /**
   * Use this method to delete an ephemeral message. Note that it is not guaranteed that the user will receive the message deletion event, especially if they are offline. Returns True on success.
   * @see https://core.telegram.org/bots/api#deleteephemeralmessage
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $receiver_user_id Identifier of the user who received the message
   * @param int $ephemeral_message_id Identifier of the ephemeral message to delete
   * @return bool
   */
  public function deleteEphemeralMessage( $chat_id, $receiver_user_id, $ephemeral_message_id )
  {
    return $this->apiCall( 'deleteEphemeralMessage', ['chat_id' => $chat_id, 'receiver_user_id' => $receiver_user_id, 'ephemeral_message_id' => $ephemeral_message_id] );
  }

  /**
   * Use this method to remove a reaction from a message in a group or a supergroup chat. The bot must have the 'can_delete_messages' administrator right in the chat. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletemessagereaction
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param int $message_id Identifier of the target message
   * @param array $options Optional parameters:
   *   - user_id (Integer) Identifier of the user whose reaction will be removed, if the reaction was added by a user
   *   - actor_chat_id (Integer) Identifier of the chat whose reaction will be removed, if the reaction was added by a chat
   * @return bool
   */
  public function deleteMessageReaction( $chat_id, $message_id, array $options = [] )
  {
    return $this->apiCall( 'deleteMessageReaction', array_merge( ['chat_id' => $chat_id, 'message_id' => $message_id], $options ) );
  }

  /**
   * Use this method to remove up to 10000 recent reactions in a group or a supergroup chat added by a given user or chat. The bot must have the 'can_delete_messages' administrator right in the chat. Returns True on success.
   * @see https://core.telegram.org/bots/api#deleteallmessagereactions
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target supergroup in the format @username
   * @param array $options Optional parameters:
   *   - user_id (Integer) Identifier of the user whose reactions will be removed, if the reactions were added by a user
   *   - actor_chat_id (Integer) Identifier of the chat whose reactions will be removed, if the reactions were added by a chat
   * @return bool
   */
  public function deleteAllMessageReactions( $chat_id, array $options = [] )
  {
    return $this->apiCall( 'deleteAllMessageReactions', array_merge( ['chat_id' => $chat_id], $options ) );
  }

  /**
   * Use this method to get a sticker set. On success, a StickerSet object is returned.
   * @see https://core.telegram.org/bots/api#getstickerset
   *
   * @param string $name Name of the sticker set
   * @return StickerSet|false
   */
  public function getStickerSet( $name )
  {
    return $this->apiCall( 'getStickerSet', ['name' => $name], StickerSet::class );
  }

  /**
   * Use this method to get information about custom emoji stickers by their identifiers. Returns an Array of Sticker objects.
   * @see https://core.telegram.org/bots/api#getcustomemojistickers
   *
   * @param array $custom_emoji_ids A JSON-serialized list of custom emoji identifiers. At most 200 custom emoji identifiers can be specified.
   * @return Sticker[]|false
   */
  public function getCustomEmojiStickers( $custom_emoji_ids )
  {
    return $this->apiCall( 'getCustomEmojiStickers', ['custom_emoji_ids' => $custom_emoji_ids], Sticker::class, true );
  }

  /**
   * Use this method to upload a file with a sticker for later use in the createNewStickerSet, addStickerToSet, or replaceStickerInSet methods (the file can be used multiple times). Returns the uploaded File on success.
   * @see https://core.telegram.org/bots/api#uploadstickerfile
   *
   * @param int $user_id User identifier of sticker file owner
   * @param \CURLFile $sticker A file with the sticker in .WEBP, .PNG, .TGS, or .WEBM format. See https://core.telegram.org/stickers for technical requirements. More information on Sending Files »
   * @param string $sticker_format Format of the sticker, must be one of “static”, “animated”, “video”
   * @return File|false
   */
  public function uploadStickerFile( $user_id, $sticker, $sticker_format )
  {
    return $this->apiCall( 'uploadStickerFile', ['user_id' => $user_id, 'sticker' => $sticker, 'sticker_format' => $sticker_format], File::class );
  }

  /**
   * Use this method to create a new sticker set owned by a user. The bot will be able to edit the sticker set thus created. Returns True on success.
   * @see https://core.telegram.org/bots/api#createnewstickerset
   *
   * @param int $user_id User identifier of created sticker set owner
   * @param string $name Short name of sticker set, to be used in t.me/addstickers/ URLs (e.g., animals). Can contain only English letters, digits and underscores. Must begin with a letter, can't contain consecutive underscores and must end in "_by_<bot_username>". <bot_username> is case insensitive. 1-64 characters.
   * @param string $title Sticker set title, 1-64 characters
   * @param array $stickers A JSON-serialized list of 1-50 initial stickers to be added to the sticker set
   * @param array $options Optional parameters:
   *   - sticker_type (String) Type of stickers in the set, pass “regular”, “mask”, or “custom_emoji”. By default, a regular sticker set is created.
   *   - needs_repainting (Boolean) Pass True if stickers in the sticker set must be repainted to the color of text when used in messages, the accent color if used as emoji status, white on chat photos, or another appropriate color based on context; for custom emoji sticker sets only
   * @return bool
   */
  public function createNewStickerSet( $user_id, $name, $title, $stickers, array $options = [] )
  {
    return $this->apiCall( 'createNewStickerSet', array_merge( ['user_id' => $user_id, 'name' => $name, 'title' => $title, 'stickers' => $stickers], $options ) );
  }

  /**
   * Use this method to add a new sticker to a set created by the bot. Emoji sticker sets can have up to 200 stickers. Other sticker sets can have up to 120 stickers. Returns True on success.
   * @see https://core.telegram.org/bots/api#addstickertoset
   *
   * @param int $user_id User identifier of sticker set owner
   * @param string $name Sticker set name
   * @param array $sticker A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set isn't changed.
   * @return bool
   */
  public function addStickerToSet( $user_id, $name, $sticker )
  {
    return $this->apiCall( 'addStickerToSet', ['user_id' => $user_id, 'name' => $name, 'sticker' => $sticker] );
  }

  /**
   * Use this method to move a sticker in a set created by the bot to a specific position. Returns True on success.
   * @see https://core.telegram.org/bots/api#setstickerpositioninset
   *
   * @param string $sticker File identifier of the sticker
   * @param int $position New sticker position in the set, zero-based
   * @return bool
   */
  public function setStickerPositionInSet( $sticker, $position )
  {
    return $this->apiCall( 'setStickerPositionInSet', ['sticker' => $sticker, 'position' => $position] );
  }

  /**
   * Use this method to delete a sticker from a set created by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletestickerfromset
   *
   * @param string $sticker File identifier of the sticker
   * @return bool
   */
  public function deleteStickerFromSet( $sticker )
  {
    return $this->apiCall( 'deleteStickerFromSet', ['sticker' => $sticker] );
  }

  /**
   * Use this method to replace an existing sticker in a sticker set with a new one. The method is equivalent to calling deleteStickerFromSet, then addStickerToSet, then setStickerPositionInSet. Returns True on success.
   * @see https://core.telegram.org/bots/api#replacestickerinset
   *
   * @param int $user_id User identifier of the sticker set owner
   * @param string $name Sticker set name
   * @param string $old_sticker File identifier of the replaced sticker
   * @param array $sticker A JSON-serialized object with information about the added sticker. If exactly the same sticker had already been added to the set, then the set remains unchanged.
   * @return bool
   */
  public function replaceStickerInSet( $user_id, $name, $old_sticker, $sticker )
  {
    return $this->apiCall( 'replaceStickerInSet', ['user_id' => $user_id, 'name' => $name, 'old_sticker' => $old_sticker, 'sticker' => $sticker] );
  }

  /**
   * Use this method to change the list of emoji assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setstickeremojilist
   *
   * @param string $sticker File identifier of the sticker
   * @param array $emoji_list A JSON-serialized list of 1-20 emoji associated with the sticker
   * @return bool
   */
  public function setStickerEmojiList( $sticker, $emoji_list )
  {
    return $this->apiCall( 'setStickerEmojiList', ['sticker' => $sticker, 'emoji_list' => $emoji_list] );
  }

  /**
   * Use this method to change search keywords assigned to a regular or custom emoji sticker. The sticker must belong to a sticker set created by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setstickerkeywords
   *
   * @param string $sticker File identifier of the sticker
   * @param array $options Optional parameters:
   *   - keywords (Array of String) A JSON-serialized list of 0-20 search keywords for the sticker with total length of up to 64 characters
   * @return bool
   */
  public function setStickerKeywords( $sticker, array $options = [] )
  {
    return $this->apiCall( 'setStickerKeywords', array_merge( ['sticker' => $sticker], $options ) );
  }

  /**
   * Use this method to change the mask position of a mask sticker. The sticker must belong to a sticker set that was created by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#setstickermaskposition
   *
   * @param string $sticker File identifier of the sticker
   * @param array $options Optional parameters:
   *   - mask_position (MaskPosition) A JSON-serialized object with the position where the mask should be placed on faces. Omit the parameter to remove the mask position.
   * @return bool
   */
  public function setStickerMaskPosition( $sticker, array $options = [] )
  {
    return $this->apiCall( 'setStickerMaskPosition', array_merge( ['sticker' => $sticker], $options ) );
  }

  /**
   * Use this method to set the title of a created sticker set. Returns True on success.
   * @see https://core.telegram.org/bots/api#setstickersettitle
   *
   * @param string $name Sticker set name
   * @param string $title Sticker set title, 1-64 characters
   * @return bool
   */
  public function setStickerSetTitle( $name, $title )
  {
    return $this->apiCall( 'setStickerSetTitle', ['name' => $name, 'title' => $title] );
  }

  /**
   * Use this method to set the thumbnail of a regular or mask sticker set. The format of the thumbnail file must match the format of the stickers in the set. Returns True on success.
   * @see https://core.telegram.org/bots/api#setstickersetthumbnail
   *
   * @param string $name Sticker set name
   * @param int $user_id User identifier of the sticker set owner
   * @param string $format Format of the thumbnail, must be one of “static” for a .WEBP or .PNG image, “animated” for a .TGS animation, or “video” for a .WEBM video
   * @param array $options Optional parameters:
   *   - thumbnail (InputFile or String) A .WEBP or .PNG image with the thumbnail, must be up to 128 kilobytes in size and have a width and height of exactly 100px, or a .TGS animation with a thumbnail up to 32 kilobytes in size (see https://core.telegram.org/stickers#animation-requirements for animated sticker technical requirements), or a .WEBM video with the thumbnail up to 32 kilobytes in size; see https://core.telegram.org/stickers#video-requirements for video sticker technical requirements. Pass a file_id as a String to send a file that already exists on the Telegram servers, pass an HTTP URL as a String for Telegram to get a file from the Internet, or upload a new one using multipart/form-data. More information on Sending Files ». Animated and video sticker set thumbnails can't be uploaded via HTTP URL. If omitted, then the thumbnail is dropped and the first sticker is used as the thumbnail.
   * @return bool
   */
  public function setStickerSetThumbnail( $name, $user_id, $format, array $options = [] )
  {
    return $this->apiCall( 'setStickerSetThumbnail', array_merge( ['name' => $name, 'user_id' => $user_id, 'format' => $format], $options ) );
  }

  /**
   * Use this method to set the thumbnail of a custom emoji sticker set. Returns True on success.
   * @see https://core.telegram.org/bots/api#setcustomemojistickersetthumbnail
   *
   * @param string $name Sticker set name
   * @param array $options Optional parameters:
   *   - custom_emoji_id (String) Custom emoji identifier of a sticker from the sticker set; pass an empty string to drop the thumbnail and use the first sticker as the thumbnail
   * @return bool
   */
  public function setCustomEmojiStickerSetThumbnail( $name, array $options = [] )
  {
    return $this->apiCall( 'setCustomEmojiStickerSetThumbnail', array_merge( ['name' => $name], $options ) );
  }

  /**
   * Use this method to delete a sticker set that was created by the bot. Returns True on success.
   * @see https://core.telegram.org/bots/api#deletestickerset
   *
   * @param string $name Sticker set name
   * @return bool
   */
  public function deleteStickerSet( $name )
  {
    return $this->apiCall( 'deleteStickerSet', ['name' => $name] );
  }

  /**
   * Use this method to send rich messages. If the message contains a block with a media element, then the bot must have the right to send the media to the chat. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendrichmessage
   *
   * @param array $rich_message The message to be sent
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent. Bot can send rich messages on behalf of a business account only if the corresponding user can send rich messages.
   *   - chat_id (Integer or String) Unique identifier for the target chat or username of the target bot, supergroup, or channel in the format @username; required unless the message is sent as an ephemeral reply to a callback query
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - direct_messages_topic_id (Integer) Identifier of the direct messages topic to which the message will be sent; required if the message is sent to a direct messages chat
   *   - ephemeral_message_parameters (EphemeralMessageParameters) A JSON-serialized object containing the parameters of the ephemeral message to send
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - suggested_post_parameters (SuggestedPostParameters) A JSON-serialized object containing the parameters of the suggested post to send; for direct messages chats only. If the message is sent as a reply to another suggested post, then that suggested post is automatically declined.
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup or ReplyKeyboardMarkup or ReplyKeyboardRemove or ForceReply) Additional interface options. A JSON-serialized object for an inline keyboard, custom reply keyboard, instructions to remove a reply keyboard or to force a reply from the user.
   * @return Message|false
   */
  public function sendRichMessage( $rich_message, array $options = [] )
  {
    return $this->apiCall( 'sendRichMessage', array_merge( ['rich_message' => $rich_message], $options ), Message::class );
  }

  /**
   * Use this method to stream a partial rich message to a user while the message is being generated. Note that the streamed draft is ephemeral and acts as a temporary 30-second preview - once the output is finalized, you must call sendRichMessage with the complete message to persist it in the user's chat. Returns True on success.
   * @see https://core.telegram.org/bots/api#sendrichmessagedraft
   *
   * @param int $chat_id Unique identifier for the target private chat
   * @param int $draft_id Unique identifier of the message draft; must be non-zero. Changes to drafts with the same identifier are animated. Otherwise, the draft is replaced without animation.
   * @param array $rich_message The partial message to be streamed. Direct upload of new files and explicit upload of files by a URL isn't supported.
   * @param array $options Optional parameters:
   *   - message_thread_id (Integer) Unique identifier for the target message thread
   *   - can_stop (Boolean) Pass True to show the user a button to stop further drafts. The bot will receive an Update “stopped_message_generation” if the user presses the button.
   *   - keep_on_stop (Boolean) Pass True to keep the draft in the chat when the button is pressed. The draft will still disappear after a short time or if the bot sends a message. To fully preserve the partial draft, the bot should send it as a new message.
   * @return bool
   */
  public function sendRichMessageDraft( $chat_id, $draft_id, $rich_message, array $options = [] )
  {
    return $this->apiCall( 'sendRichMessageDraft', array_merge( ['chat_id' => $chat_id, 'draft_id' => $draft_id, 'rich_message' => $rich_message], $options ) );
  }

  /**
   * Use this method to send answers to an inline query. On success, True is returned. No more than 50 results per query are allowed.
   * @see https://core.telegram.org/bots/api#answerinlinequery
   *
   * @param string $inline_query_id Unique identifier for the answered query
   * @param array $results A JSON-serialized Array of results for the inline query
   * @param array $options Optional parameters:
   *   - cache_time (Integer) The maximum amount of time in seconds that the result of the inline query may be cached on the server. Defaults to 300.
   *   - is_personal (Boolean) Pass True if results may be cached on the server side only for the user that sent the query. By default, results may be returned to any user who sends the same query.
   *   - next_offset (String) Pass the offset that a client should send in the next query with the same text to receive more results. Pass an empty string if there are no more results or if you don't support pagination. Offset length can't exceed 64 bytes.
   *   - button (InlineQueryResultsButton) A JSON-serialized object describing a button to be shown above inline query results
   * @return bool
   */
  public function answerInlineQuery( $inline_query_id, $results, array $options = [] )
  {
    return $this->apiCall( 'answerInlineQuery', array_merge( ['inline_query_id' => $inline_query_id, 'results' => $results], $options ) );
  }

  /**
   * Allows the bot to cancel or re-enable extension of a subscription paid in Telegram Stars. Returns True on success.
   * @see https://core.telegram.org/bots/api#edituserstarsubscription
   *
   * @param int $user_id Identifier of the user whose subscription will be edited
   * @param string $telegram_payment_charge_id Telegram payment identifier for the subscription
   * @param bool $is_canceled Pass True to cancel extension of the user subscription; the subscription must be active up to the end of the current subscription period. Pass False to allow the user to re-enable a subscription that was previously canceled by the bot.
   * @return bool
   */
  public function editUserStarSubscription( $user_id, $telegram_payment_charge_id, $is_canceled )
  {
    return $this->apiCall( 'editUserStarSubscription', ['user_id' => $user_id, 'telegram_payment_charge_id' => $telegram_payment_charge_id, 'is_canceled' => $is_canceled] );
  }

  /**
   * Informs a user that some of the Telegram Passport elements they provided contains errors. The user will not be able to re-submit their Passport to you until the errors are fixed (the contents of the field for which you returned the error must change). Returns True on success.
   * Use this if the data submitted by the user doesn't satisfy the standards your service requires for any reason. For example, if a birthday date seems invalid, a submitted document is blurry, a scan shows evidence of tampering, etc. Supply some details in the error message to make sure the user knows how to correct the issues.
   * @see https://core.telegram.org/bots/api#setpassportdataerrors
   *
   * @param int $user_id User identifier
   * @param array $errors A JSON-serialized Array describing the errors
   * @return bool
   */
  public function setPassportDataErrors( $user_id, $errors )
  {
    return $this->apiCall( 'setPassportDataErrors', ['user_id' => $user_id, 'errors' => $errors] );
  }

  /**
   * Use this method to send a game. On success, the sent Message is returned.
   * @see https://core.telegram.org/bots/api#sendgame
   *
   * @param int|string $chat_id Unique identifier for the target chat or username of the target bot in the format @username. Games can't be sent to channel direct messages chats and channel chats.
   * @param string $game_short_name Short name of the game, serves as the unique identifier for the game. Set up your games via @BotFather.
   * @param array $options Optional parameters:
   *   - business_connection_id (String) Unique identifier of the business connection on behalf of which the message will be sent
   *   - message_thread_id (Integer) Unique identifier for the target message thread (topic) of a forum; for forum supergroups and private chats of bots with forum topic mode enabled only
   *   - disable_notification (Boolean) Sends the message silently. Users will receive a notification with no sound.
   *   - protect_content (Boolean) Protects the contents of the sent message from forwarding and saving
   *   - allow_paid_broadcast (Boolean) Pass True to allow up to 1000 messages per second, ignoring broadcasting limits for a fee of 0.1 Telegram Stars per message. The relevant Stars will be withdrawn from the bot's balance.
   *   - message_effect_id (String) Unique identifier of the message effect to be added to the message; for private chats only
   *   - reply_parameters (ReplyParameters) Description of the message to reply to
   *   - reply_markup (InlineKeyboardMarkup) A JSON-serialized object for an inline keyboard. If empty, one 'Play game_title' button will be shown. If not empty, the first button must launch the game.
   * @return Message|false
   */
  public function sendGame( $chat_id, $game_short_name, array $options = [] )
  {
    return $this->apiCall( 'sendGame', array_merge( ['chat_id' => $chat_id, 'game_short_name' => $game_short_name], $options ), Message::class );
  }

  /**
   * Use this method to set the score of the specified user in a game message. On success, if the message is not an inline message, the Message is returned, otherwise True is returned. Returns an error, if the new score is not greater than the user's current score in the chat and force is False.
   * @see https://core.telegram.org/bots/api#setgamescore
   *
   * @param int $user_id User identifier
   * @param int $score New score, must be non-negative
   * @param array $options Optional parameters:
   *   - force (Boolean) Pass True if the high score is allowed to decrease. This can be useful when fixing mistakes or banning cheaters.
   *   - disable_edit_message (Boolean) Pass True if the game message should not be automatically edited to include the current scoreboard
   *   - chat_id (Integer) Required if inline_message_id is not specified. Unique identifier for the target chat.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the sent message.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   * @return Message|bool
   */
  public function setGameScore( $user_id, $score, array $options = [] )
  {
    return $this->apiCall( 'setGameScore', array_merge( ['user_id' => $user_id, 'score' => $score], $options ), Message::class );
  }

  /**
   * Use this method to get data for high score tables. Will return the score of the specified user and several of their neighbors in a game. Returns an Array of GameHighScore objects.
   * This method will currently return scores for the target user, plus two of their closest neighbors on each side. Will also return the top three users if the user and their neighbors are not among them. Please note that this behavior is subject to change.
   * @see https://core.telegram.org/bots/api#getgamehighscores
   *
   * @param int $user_id Target user id
   * @param array $options Optional parameters:
   *   - chat_id (Integer) Required if inline_message_id is not specified. Unique identifier for the target chat.
   *   - message_id (Integer) Required if inline_message_id is not specified. Identifier of the sent message.
   *   - inline_message_id (String) Required if chat_id and message_id are not specified. Identifier of the inline message.
   * @return GameHighScore[]|false
   */
  public function getGameHighScores( $user_id, array $options = [] )
  {
    return $this->apiCall( 'getGameHighScores', array_merge( ['user_id' => $user_id], $options ), GameHighScore::class, true );
  }

}
