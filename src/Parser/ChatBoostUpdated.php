<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a boost added to a chat or changed.
 * @see https://core.telegram.org/bots/api#chatboostupdated
 *
 * @method Chat chat() Chat which was boosted
 * @method ChatBoost boost() Information about the chat boost
 */
class ChatBoostUpdated extends Base
{

  protected $wrapper = [
    'chat' => Chat::class,
    'boost' => ChatBoost::class,
  ];

}
