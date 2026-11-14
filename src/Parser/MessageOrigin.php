<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the origin of a message. It can be one of
 * Currently can be one of MessageOriginUser, MessageOriginHiddenUser, MessageOriginChat, MessageOriginChannel — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#messageorigin
 *
 * @method string type() Type of the message origin, always “user”
 * @method integer date() Date the message was sent originally in Unix time
 * @method User sender_user() MessageOriginUser only. User that sent the message originally
 * @method string sender_user_name() MessageOriginHiddenUser only. Name of the user that sent the message originally
 * @method Chat sender_chat() MessageOriginChat only. Chat that sent the message originally
 * @method string author_signature() MessageOriginChat, MessageOriginChannel only. Optional. For messages originally sent by an anonymous chat administrator, original message author signature
 * @method Chat chat() MessageOriginChannel only. Channel chat to which the message was originally sent
 * @method integer message_id() MessageOriginChannel only. Unique message identifier inside the chat
 */
class MessageOrigin extends Base
{

  protected $wrapper = [
    'sender_user' => User::class,
    'sender_chat' => Chat::class,
    'chat' => Chat::class,
  ];

}
