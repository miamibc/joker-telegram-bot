<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

use TelegramBot\Api\Types\Update;
use TelegramBot\Api\Client;

abstract class Plugin
{
  public function processUpdate( Update $update, Client $client ){}
}