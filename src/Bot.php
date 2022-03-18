<?php

/**
 * Joker Telegram Bot
 *
 * Born in 2001'th this bot was our entertainment chat bot made with miRCscript, joking on channel
 * #blackcrystal in Quakenet.
 *
 * In 2019 we started to create new Joker for Telegram on PHP, with more flexibility and functionality added.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

use Joker\Parser\Message;
use Joker\Parser\Update;
use Joker\Parser\User;

declare(ticks=1);

class Bot
{

  const PLUGIN_NEXT   = true;
  const PLUGIN_BREAK  = false;

  private
    $token = null,
    $debug = false,
    $ch = null,
    $buffer = [],
    $me = null,
    $last_update_id = 0,
    $plugins = []
  ;

  public function __construct( $token, $debug = false )
  {

    // No token given, start bot without access HTTP and Telegram Bot API, you can test something else...
    // @see QuoteTest::testTelegramQuoteConverter
    if (!$token) return;

    $this->token = $token;
    $this->debug = $debug;
    $this->ch = curl_init();

    // display information, or throw an error
    $this->me = $this->getMe();
    if (!$this->me->id())
    {
      throw new Exception("Wrong or inactive Telegram API token. More info https://core.telegram.org/bots#6-botfather");
    }
    echo "\nBot started: "; print_r( $this->me->getData() );

    // intercept ctrl+c
    pcntl_signal(SIGINT, [$this,'quit']);
    pcntl_signal(SIGTERM, [$this,'quit']);

  }

  /**
   * @param $method
   * @param $data
   *
   * @return Update|false
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
    ]);

    $plain_response = curl_exec($this->ch);
    $result = json_decode( $plain_response, true);
    $this->log( $method . ' '. $plain_request . ' => ' . $plain_response );

    if (!isset($result['ok']) || !$result['ok'])
      // throw new Exception("Something went wrong");
      return false;

    return isset($result['result']) ? $result['result'] : false;
  }

  /**
   * @param $method
   * @param $data
   *
   * @return Update|false
   * @throws Exception
   */
  private function _requestMultipart($method,$data = [])
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
      CURLOPT_POSTFIELDS     => $data,        // this are my post vars
      CURLOPT_HTTPHEADER     => [
        'Content-Type: multipart/form-data',
        'Connection: Keep-Alive',
      ],
    ]);

    $plain_response = curl_exec($this->ch);
    $result = json_decode( $plain_response, true);
    $this->log( $method . ' '. json_encode($data) . ' => ' . $plain_response );

    if (!isset($result['ok']) || !$result['ok'])
      // throw new Exception("Something went wrong");
      return false;

    return isset($result['result']) ? $result['result'] : false;
  }

  public function loop()
  {
    // if empty buffer, request updates
    if (empty($this->buffer))
    {
      if (!$updates = $this->getUpdates($this->last_update_id)) $updates = [];
      foreach ($updates as $item)
      {
        $this->buffer[] = new Update( $item, $this );
        $this->last_update_id = $item['update_id'] + 1;
      }
    }

    // get top update from buffer, or create empty update
    $update = empty($this->buffer) ? new Update( null, $this) : array_shift( $this->buffer );
    $tags   = $update->getTags();

    // scan all plugins
    foreach ( $this->plugins as $plugin )
    {
      // iterate plugin public methods
      foreach ( get_class_methods($plugin) as $method )
      {
        // make array of pieces, splitted by Uppercase letter
        // f.e. onSomeMethod => [ on, Some, Method ]
        $pieces = preg_split('/(?=[A-Z])/',$method);

        // method must start from 'on', and must have other parts
        if (array_shift($pieces) !== 'on' || count($pieces) == 0) continue;

        // count score to know, do we need to execute this method
        $score = 0;
        foreach ( $pieces as $piece)
          if (isset($tags[$piece]) && $tags[$piece])
            $score++;

        // if score doesnt match number of pieces, we skip this method
        if ($score !== count($pieces)) continue;

        // at last, execute it
        try
        {
          $result = call_user_func([$plugin,$method],$update);
        }
        catch (\Exception $exception)
        {
          $this->log("Exception $exception");
          $result = Bot::PLUGIN_BREAK;
        }

        // check return value to change plugin processing behaviour if needed
        if     ($result === Bot::PLUGIN_NEXT)  { break 1; }
        elseif ($result === Bot::PLUGIN_BREAK) { break 2; }
        elseif ($result === true ) { break 1; }
        elseif ($result === false) { break 2; }
      }
    }

    sleep(1);
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

  public function console( $message )
  {
    $timestamp = date("Y-m-d H:i:s");
    $json = is_string($message) ? $message : json_encode($message, JSON_PRETTY_PRINT);
    echo "\n[$timestamp] $json";
    return $message;
  }

  /**
   * @param Plugin\Base[] $plugins
   * @return $this
   */
  public function plug( array $plugins )
  {
    $this->plugins = $plugins;
    return $this;
  }

  /** ctrl-c and ctrl+break event processing */
  public function quit( $event = null )
  {
    foreach ($this->plugins as $i=>$plugin)
    {
      $this->log("Unloading " . get_class($plugin));
      unset($this->plugins[$i]);
    }

    echo "\nBuj :p";
    exit();
  }


  public function getMe()
  {
    $data = $this->_request('getMe');
    return new User( $data );
  }

  /**
   * Perform custom request to Telegram API
   * @param $method
   * @param $data
   *
   * @return array|bool
   */
  public function customRequest( $method, $data )
  {
    return $this->_request( $method, $data );
  }

  public function getUpdates( $offset )
  {
    return $this->_request("getUpdates",['offset' => $offset]);
  }

  public function sendMessage( $chat_id, $text, $options = [])
  {
    $result = $this->_request("sendMessage", array_merge(["chat_id" =>$chat_id,"text" =>$text], $options) );
    return new Message( $result );
  }

  public function sendSticker( $chat_id, $file_id, $options = [])
  {
    $result = $this->_request("sendSticker", array_merge(["chat_id" =>$chat_id,"sticker" =>$file_id], $options) );
    return new Message( $result );
  }

  public function deleteMessage( $chat_id, $message_id)
  {
    $result = $this->_request("deleteMessage", ["chat_id" =>$chat_id,"message_id" =>$message_id] );
    return (bool)$result;
  }

  public function banChatMember( $chat_id, $user_id, $bantime = 600)
  {
    $result = $this->_request("banChatMember", ["chat_id" =>$chat_id,"user_id" =>$user_id, "until_date" => time()+$bantime] );
    return (bool)$result;
  }

  public function sendPhoto( $chat_id, $file, $options = [] )
  {
    if (!file_exists($file)) return false;
    $result = $this->_requestMultipart( 'sendPhoto', array_merge( [ 'chat_id'=>$chat_id, 'photo'=>new \CURLFile( $file ) ], $options ));
    return new Message( $result );
  }

  public function sendAudio( $chat_id, $file, $options = [] )
  {
    if (!file_exists($file)) return false;
    $result = $this->_requestMultipart( 'sendAudio', array_merge( [ 'chat_id'=>$chat_id, 'audio'=>new \CURLFile( $file ) ], $options ));
    return new Message( $result );
  }

  public function forwardMessage( $chat_id, $from_chat_id, $message_id, $options = [] )
  {
    $result = $this->_request( 'forwardMessage', array_merge( [ 'chat_id'=>$chat_id, 'from_chat_id'=>$from_chat_id, 'message_id' => $message_id ], $options ));
    return new Message( $result );
  }

}