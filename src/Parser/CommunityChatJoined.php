<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a chat being joined by a user from a community.
 * @see https://core.telegram.org/bots/api#communitychatjoined
 *
 * @method Community community() The community from which the chat was joined
 */
class CommunityChatJoined extends Base
{

  protected $wrapper = [
    'community' => Community::class,
  ];

}
