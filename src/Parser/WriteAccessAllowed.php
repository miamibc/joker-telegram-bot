<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about a user allowing a bot to write messages after adding it to the attachment menu, launching a Web App from a link, or accepting an explicit request from a Web App sent by the method requestWriteAccess.
 * @see https://core.telegram.org/bots/api#writeaccessallowed
 *
 * @method bool from_request() Optional. True, if the access was granted after the user accepted an explicit request from a Web App sent by the method requestWriteAccess
 * @method string web_app_name() Optional. Name of the Web App, if the access was granted when the Web App was launched from a link
 * @method bool from_attachment_menu() Optional. True, if the access was granted when the bot was added to the attachment or side menu
 */
class WriteAccessAllowed extends Base
{

  protected $wrapper = [
  ];

}
