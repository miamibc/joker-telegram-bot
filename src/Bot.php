<?php
/**
 * Joker the Telegram bot
 *
 * Born in 2001'th this bot was entertaiment chatbot made in miRCscript,
 * joking on channel #blackcrystal in Quakenet. Since that year many things
 * has been changed. Here's third rewrite of Joker on PHP and Telegram API.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 *
 * @property  \TelegramBot\Api\BotApi|\TelegramBot\Api\Client $client
 * @property  \Joker\Plugin $plugin
 */

namespace Joker;

use TelegramBot\Api\Client;
use TelegramBot\Api\Types\Update;

class Bot
{

  const EVENT_BREAK = 100500;

  private
    $client = null,
    $input_buffer = [],
    $last_update_id = 0;

  public function __construct( $token )
  {
    $this->client = new Client( $token );
  }

  public function loop()
  {
    $this->getUpdates();

    $update = array_shift( $this->input_buffer );
    if (!$update) $update = new Update();

    foreach ( $this->plugins as $plugin )
    {
      try {
        $result = $plugin->processUpdate($update, $this->client);
        if ($result === Bot::EVENT_BREAK) { break; }
        elseif ( $result === false) { break; }
      }
      catch ( Joker\Exception $exception)
      {
        $this->debug($exception);
      }
      catch ( \Exception $exception )
      {
        $this->debug($exception);
      }
    }
  }

  private function getUpdates()
  {
    foreach ( $this->client->getUpdates( $this->last_update_id ) as $item)
    {
      $this->input_buffer[] = $item;
      $this->last_update_id = $item->getUpdateId();
    }
  }

  public function debug( $message, $log = 'debug.log' )
  {
    if (!is_string( $message ))
      $message = json_encode( $message );

    $timestamp = date("Y-m-d H:i:s");
    file_put_contents( dirname(__FILE__) . '/log/' . $log, "[$timestamp] $message", FILE_APPEND);
  }


}