<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents reaction changes on a message with anonymous reactions.
 * @see https://core.telegram.org/bots/api#messagereactioncountupdated
 *
 * @method Chat chat() The chat containing the message
 * @method integer message_id() Unique message identifier inside the chat
 * @method integer date() Date of the change in Unix time
 * @method ReactionCount[] reactions() List of reactions that are present on the message
 */
class MessageReactionCountUpdated extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
    'reactions' => ReactionCount::class,
  ];

}
