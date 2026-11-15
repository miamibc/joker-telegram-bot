<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes a backdrop, which can be used as the background of a theme of the screen for sharing a link to a user or a chat.
 * @see https://core.telegram.org/bots/api#chatsharethemebackdrop
 *
 * @method integer id() Unique identifier of the backdrop
 * @method string name() Name of the backdrop
 * @method UniqueGiftBackdropColors colors() Colors of the backdrop
 */
class ChatShareThemeBackdrop extends Base
{

  protected $wrapper = [
    'colors' => UniqueGiftBackdropColors::class,
  ];

}
