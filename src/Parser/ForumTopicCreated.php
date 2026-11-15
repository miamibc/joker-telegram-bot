<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about a new forum topic created in the chat.
 * @see https://core.telegram.org/bots/api#forumtopiccreated
 *
 * @method string name() Name of the topic
 * @method integer icon_color() Color of the topic icon in RGB format
 * @method string icon_custom_emoji_id() Optional. Unique identifier of the custom emoji shown as the topic icon
 * @method bool is_name_implicit() Optional. True, if the name of the topic wasn't specified explicitly by its creator and likely needs to be changed by the bot
 */
class ForumTopicCreated extends Base
{

  protected $wrapper = [
  ];

}
