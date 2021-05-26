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
 * @method integer message_id() Unique message identifier inside this chat
 * @method User from() Optional. Sender, empty for messages sent to channels
 * @method Chat sender_chat() Optional. Sender of the message, sent on behalf of a chat. The channel itself for channel messages. The supergroup itself for messages from anonymous group administrators. The linked channel for messages automatically forwarded to the discussion group
 * @method integer date() Date the message was sent in Unix time
 * @method Chat chat() Conversation the message belongs to
 * @method User forward_from() Optional. For forwarded messages, sender of the original message
 * @method Chat forward_from_chat() Optional. For messages forwarded from channels or from anonymous administrators, information about the original sender chat
 * @method Message reply_to_message() Optional. For replies, the original message. Note that the Message object in this field will not contain further reply_to_message fields even if it itself is a reply.
 * @method User via_bot() Optional. Bot through which the message was sent
 * @method string edit_date() Optional. Date the message was last edited in Unix time
 * @method Text text() Optional. For text messages, the actual UTF-8 text of the message, 0-4096 characters
 * @method MessageEntity[] entities() Optional. For text messages, special entities like usernames, URLs, bot commands, etc. that appear in the text
 * @method Animation animation() Optional. Message is an animation, information about the animation. For backward compatibility, when this field is set, the document field will also be set
 * @method Audio audio() Optional. Message is an audio file, information about the file
 * @method Document document() Optional. Message is a general file, information about the file
 * @method PhotoSize[] photo() Optional. Message is a photo, available sizes of the photo
 * @method Sticker sticker() Optional. Message is a sticker, information about the sticker
 * @method Video video() Optional. Message is a video, information about the video
 * @method Voice voice() Optional. Message is a voice message, information about the file
 * @method string caption() Optional. Caption for the animation, audio, document, photo, video or voice, 0-1024 characters
 * @method Contact contact() Optional. Message is a shared contact, information about the contact
 * @method Dice dice() Optional. Message is a dice with random value
 * @method Game game() Optional. Message is a game, information about the game.
 * @method Poll poll() Optional. Message is a native poll, information about the poll
 * @method Venue venue() Optional. Message is a venue, information about the venue. For backward compatibility, when this field is set, the location field will also be set
 * @method Location location() Optional. Message is a shared location, information about the location
 * @method User new_chat_member() Optional. New members that were added to the group or supergroup and information about them (the bot itself may be one of these members)
 * @method User left_chat_member() Optional. A member was removed from the group, information about them (this member may be the bot itself)
 * @method Message pinned_message() Optional. Specified message was pinned. Note that the Message object in this field will not contain further reply_to_message fields even if it is itself a reply.
 */
class Message extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'sender_chat' => Chat::class,
    'chat' => Chat::class,
    'forward_from' => User::class,
    'forward_from_chat' => Chat::class,
    'reply_to_message' => Message::class,
    'via_bot' => User::class,
    'text' => Text::class,
    'entities' => MessageEntity::class,
    'animation' => Animation::class,
    'audio' => Audio::class,
    'document' => Document::class,
    'photo' => PhotoSize::class,
    'sticker' => Sticker::class,
    'video' => Video::class,
    'voice' => Voice::class,
    'contact' => Contact::class,
    'dice' => Dice::class,
    'game' => Game::class,
    'poll' => Poll::class,
    'venue' => Venue::class,
    'location' => Location::class,
    'new_chat_member' => User::class,
    'left_chat_member' => User::class,
    'pinned_message' => Message::class,
  ];

  public function id()
  {
    return $this->message_id();
  }

}