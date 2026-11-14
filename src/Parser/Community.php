<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Represents a community (a group of chats).
 * @see https://core.telegram.org/bots/api#community
 *
 * @method integer id() Unique identifier for this community. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a signed 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method string name() Name of the community
 */
class Community extends Base
{

  protected $wrapper = [
  ];

}
