<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a service message about a chat or a bot being added to a community.
 * @see https://core.telegram.org/bots/api#communitychatadded
 *
 * @method Community community() The new community to which the chat or the bot belongs
 */
class CommunityChatAdded extends Base
{

  protected $wrapper = [
    'community' => Community::class,
  ];

}
