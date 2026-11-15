<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes a Web App.
 * @see https://core.telegram.org/bots/api#webappinfo
 *
 * @method string url() An HTTPS URL of a Web App to be opened with additional data as specified in Initializing Web Apps
 */
class WebAppInfo extends Base
{

  protected $wrapper = [
  ];

}
