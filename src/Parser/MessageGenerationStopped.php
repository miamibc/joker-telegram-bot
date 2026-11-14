<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes an update about a user stopping message generation.
 * @see https://core.telegram.org/bots/api#messagegenerationstopped
 *
 * @method Chat chat() Chat in which the message is generated
 * @method integer message_thread_id() Optional. Unique identifier of the message thread in which the message is generated
 * @method integer draft_id() Unique identifier of the message draft which was stopped
 */
class MessageGenerationStopped extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
  ];

}
