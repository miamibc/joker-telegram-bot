<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class QuotePlugin extends Plugin
{
  public function processUpdate(Update $update,Client $client)
  {

    $message = $update->getMessage();
    if (!$message) return;

    $chat = $message->getChat();
    if (!$chat) return;

    $text = $message->getText();
    if (!$text) return;



  }
}