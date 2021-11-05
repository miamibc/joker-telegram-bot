<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

use Joker\Database\Sqlite;

/**
 * This object represents a chat.
 * @see https://core.telegram.org/bots/api#chat
 *
 * @method int id() Unique identifier for this chat. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method string type() Type of chat, can be either “private”, “group”, “supergroup” or “channel”
 * @method string title() Optional. Title, for supergroups, channels and group chats
 * @method string username() Optional. Username, for private chats, supergroups and channels if available
 * @method string first_name() Optional. First name of the other party in a private chat
 * @method string last_name()  Optional. Last name of the other party in a private chat
 * @method ChatPhoto photo() Optional. Chat photo. Returned only in getChat.
 * @method string bio() Optional. Bio of the other party in a private chat. Returned only in getChat.
 * @method string description() Optional. Description, for groups, supergroups and channel chats. Returned only in getChat.
 * @method string invite_link() Optional. Primary invite link, for groups, supergroups and channel chats. Returned only in getChat.
 * @method Message pinned_message() Optional. The most recent pinned message (by sending date). Returned only in getChat.
 */
class Chat extends Base
{

  use Sqlite;

  protected $wrapper = [
    'photo' => ChatPhoto::class,
    'pinned_message' => Message::class,
  ];

  public function __toString()
  {
    return $this->name();
  }

  public function name()
  {
    if (isset($this->data['title']))
      return $this->data['title'];
    if (isset($this->data['username']))
      return $this->data['username'];
    return "Unknown";
  }

  /**
   * Ban user from chat
   *
   * @param User $user
   * @param int $bantime
   * @return bool
   */
  public function banChatMember( User $user, $bantime = 600)
  {
    return $this->bot()->banChatMember( $this->id(), $user->id(), $bantime );
  }

  /**
   * Sent message to chat
   *
   * @param $message
   * @param array $options
   * @return Message
   */
  public function sendMessage( $message, $options = [] )
  {
    return $this->bot()->sendMessage($message, $options );
  }

}