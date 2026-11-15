<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about an edited forum topic.
 * @see https://core.telegram.org/bots/api#forumtopicedited
 *
 * @method string name() Optional. New name of the topic, if it was edited
 * @method string icon_custom_emoji_id() Optional. New identifier of the custom emoji shown as the topic icon, if it was edited; an empty string if the icon was removed
 */
class ForumTopicEdited extends Base
{

  protected $wrapper = [
  ];

}
