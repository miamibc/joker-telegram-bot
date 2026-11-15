<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object contains a list of backdrops, which can be used as the background of a theme of the screen for sharing a link to a user or a chat.
 * @see https://core.telegram.org/bots/api#chatsharethemebackdrops
 *
 * @method ChatShareThemeBackdrop[] backdrops() The list of backdrops
 */
class ChatShareThemeBackdrops extends Base
{

  protected $wrapper = [
    'backdrops' => ChatShareThemeBackdrop::class,
  ];

}
