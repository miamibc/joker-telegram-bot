<?php

/**
 * Joker Bot
 *
 * Born in 2001'th this bot was entertainment chatbot made in miRCscript,
 * joking on channel #blackcrystal in Quakenet. Since that year many things
 * has been changed. Here's third rewrite of Joker on PHP and Telegram API.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 *
 * @property  Plugin $plugin
 */

namespace Joker;

class Bot
{

  const PLUGIN_NEXT   = 100500;
  const PLUGIN_BREAK  = 100501;

  private
    $debug = false,
    $ch = null,
    $token = null,
    $buffer = [],
    $last_update_id = 0,
    $plugins = [];

  public function __construct( $token, $debug = false )
  {
    if ( strlen($token) < 40)
      throw new Exception("Please provide Telegram API token. More info https://core.telegram.org/bots#3-how-do-i-create-a-bot");

    $this->token = $token;
    $this->debug = $debug;
    $this->ch = curl_init();
  }

  /**
   * @param $method
   * @param $data
   *
   * @return array|bool
   * @throws Exception
   */
  private function _request($method,$data = [])
  {
    curl_setopt_array($this->ch, [
      CURLOPT_URL => $url = "https://api.telegram.org/bot{$this->token}/{$method}",
      CURLOPT_RETURNTRANSFER => true,         // return web page
      CURLOPT_HEADER         => false,        // don't return headers
      CURLOPT_FOLLOWLOCATION => true,         // follow redirects
      CURLOPT_USERAGENT      => "joker_the_bot (+https://github.com/miamibc/joker-telegram-bot)", // who am i
      CURLOPT_AUTOREFERER    => true,         // set referer on redirect
      CURLOPT_CONNECTTIMEOUT => 120,          // timeout on connect
      CURLOPT_TIMEOUT        => 120,          // timeout on response
      CURLOPT_MAXREDIRS      => 10,           // stop after 10 redirects
      CURLOPT_POST           => true,         // i am sending post data
      CURLOPT_POSTFIELDS     => $plain_request = json_encode($data),    // this are my post vars
      CURLOPT_HTTPHEADER     => [
        'Content-Type: application/json',
        'Connection: Keep-Alive',
      ],
      // CURLOPT_SSL_VERIFYHOST => false,      // don't verify ssl
      // CURLOPT_SSL_VERIFYPEER => false,      //
      // CURLOPT_VERBOSE        => 1           //
    ]);

    $plain_response = curl_exec($this->ch);
    $result = json_decode( $plain_response, true);
    $this->log( $method . ' '. $plain_request . ' => ' . $plain_response );

    if (!isset($result['ok']) || !$result['ok'])
      throw new Exception("Something went wrong");

    return isset($result['result']) ? $result['result'] : false;
  }

  public function loop()
  {

    // request new updates
    try { $this->requestUpdates(); }
    catch ( Exception $exception){ $this->log($exception); }

    $event = new Event( $this, array_shift($this->buffer) );
    try { $this->processEvent( $event ); }
    catch ( Exception $exception)   { $this->log($exception); }

    $event = null;
    unset($event);

    // sleep a bit
    $time = count($this->buffer) ? 2 : 4;
    sleep($time);
  }

  /**
   * @throws Exception
   */
  private function requestUpdates()
  {
    foreach ($this->_request("getUpdates", ['offset' =>$this->last_update_id]) as $item)
    {
      $this->buffer[] = $item;
      $this->last_update_id = $item['update_id']+1;
    }
  }

  public function sendMessage( $chat_id, $text, $options = [])
  {
    $result = $this->_request("sendMessage", array_merge(["chat_id" =>$chat_id,"text" =>$text], $options) );
    return $result;
  }

  public function sendSticker( $chat_id, $file_id, $options = [])
  {
    $result = $this->_request("sendSticker", array_merge(["chat_id" =>$chat_id,"sticker" =>$file_id], $options) );
    return $result;
  }

  public function deleteMessage( $chat_id, $message_id)
  {
    $result = $this->_request("deleteMessage", ["chat_id" =>$chat_id,"message_id" =>$message_id] );
    return $result;
  }

  public function customRequest( $method, $data )
  {
    return $this->_request( $method, $data );
  }

  private function processEvent(Event $event )
  {
    // get event tags
    $tags = $event->getTags();

    foreach ( $this->plugins as $plugin )
    {
      foreach ( get_class_methods($plugin) as $method )
      {
        // make array of pieces, splitted by Uppercase letter
        // f.e. onSomeMethod => [ on, Some, Method ]
        $pieces = preg_split('/(?=[A-Z])/',$method);

        // method must start from on, and other parts
        if (array_shift($pieces) !== 'on' || count($pieces) == 0) continue;

        // count score to know, do we need to execute this method
        $score = 0;
        foreach ( $pieces as $piece)
        {
          // cleanup and normalize piece
          $piece = strtolower( trim( $piece, ' _'));
          if (isset($tags[$piece]) && $tags[$piece]) $score++;
        }
        // if score doesnt match number of pieces, we skip this method
        if ($score !== count($pieces)) continue;

        // at last, execute it
        $result = call_user_func( [$plugin,$method], $event );

        // check return value to change plugin processing behaviour if needed
        if     ($result === Bot::PLUGIN_NEXT)  { break 1; }
        elseif ($result === Bot::PLUGIN_BREAK) { break 2; }
        elseif ($result === true ) { break 1; }
        elseif ($result === false) { break 2; }
      }
    }
  }

  public function log( $message )
  {
    if ($this->debug)
    {
      $timestamp = date("Y-m-d H:i:s");
      $json = is_string($message) ? $message : json_encode($message);
      echo "\n[$timestamp] $json";
    }
    return $message;
  }

  /**
   * @param Plugin[] $plugins
   * @return $this
   */
  public function plug( array $plugins )
  {
    $this->plugins = $plugins;
    return $this;
  }

}