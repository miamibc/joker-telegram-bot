<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object describes the bot's menu button in a private chat. It should be one of
 * If a menu button other than MenuButtonDefault is set for a private chat, then it is applied in the chat. Otherwise the default menu button is applied. By default, the menu button opens the list of bot commands.
 * Currently can be one of MenuButtonCommands, MenuButtonWebApp, MenuButtonDefault — all of them are parsed by this class.
 * @see https://core.telegram.org/bots/api#menubutton
 *
 * @method string type() Type of the button, must be commands
 * @method string text() MenuButtonWebApp only. Text on the button
 * @method WebAppInfo web_app() MenuButtonWebApp only. Description of the Web App that will be launched when the user presses the button. The Web App will be able to send an arbitrary message on behalf of the user using the method answerWebAppQuery. Alternatively, a t.me link to a Web App of the bot can be specified in the object instead of the Web App's URL, in which case the Web App will be opened as if the user pressed the link.
 */
class MenuButton extends Base
{

  protected $wrapper = [
    'web_app' => WebAppInfo::class,
  ];

}
