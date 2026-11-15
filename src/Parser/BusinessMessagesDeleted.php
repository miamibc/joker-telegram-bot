<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object is received when messages are deleted from a connected business account.
 * @see https://core.telegram.org/bots/api#businessmessagesdeleted
 *
 * @method string business_connection_id() Unique identifier of the business connection
 * @method Chat chat() Information about a chat in the business account. The bot may not have access to the chat or the corresponding user.
 * @method integer[] message_ids() The list of identifiers of deleted messages in the chat of the business account
 */
class BusinessMessagesDeleted extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
  ];

}
