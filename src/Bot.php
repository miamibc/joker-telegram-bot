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
use Joker\Parser\StarAmount;
use Joker\Parser\StarTransactions;
use Joker\Parser\Update;
use Joker\Parser\User;

declare(ticks=1);

class Bot
{

  // all other Telegram Bot API methods
  use BotApi;

  const PLUGIN_NEXT   = true;
  const PLUGIN_BREAK  = false;

  // methods returning Message posted or edited by bot, their results are put to buffer with update_id null for Log plugin
  const METHODS_TO_LOG = [
    'sendMessage'     => 'message',
    'sendPhoto'       => 'message',
    'sendLivePhoto'   => 'message',
    'sendAudio'       => 'message',
    'sendDocument'    => 'message',
    'sendVideo'       => 'message',
    'sendAnimation'   => 'message',
    'sendVoice'       => 'message',
    'sendVideoNote'   => 'message',
    'sendSticker'     => 'message',
    'sendWidget'      => 'message',
    'sendSharedWidget'=> 'message',
    'sendPaidMedia'   => 'message',
    'sendMediaGroup'  => 'message', // array of messages
    'sendLocation'    => 'message',
    'sendVenue'       => 'message',
    'sendContact'     => 'message',
    'sendPoll'        => 'message',
    'sendChecklist'   => 'message',
    'sendDice'        => 'message',
    'sendRichMessage' => 'message',
    'sendGame'        => 'message',
    'sendInvoice'     => 'message',
    'forwardMessage'  => 'message',
    'editMessageText'         => 'edited_message',
    'editMessageCaption'      => 'edited_message',
    'editMessageMedia'        => 'edited_message',
    'editMessageLiveLocation' => 'edited_message',
    'stopMessageLiveLocation' => 'edited_message',
    'editMessageChecklist'    => 'edited_message',
    'editMessageReplyMarkup'  => 'edited_message',
  ];

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
    if (!$this->getMe()->id())
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
  protected function _request($method,$data = [])
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

    $methods_to_log = self::METHODS_TO_LOG;
    if (array_key_exists( $method, $methods_to_log ) && is_array( $result['result'] ?? null ))
      $this->buffer[] = new Update(['update_id' => null, $methods_to_log[$method] => $result['result'] ], $this);

    return isset($result['result']) ? $result['result'] : false;
  }

  /**
   * @param $method
   * @param $data
   *
   * @return Update|false
   * @throws Exception
   */
  protected function _requestMultipart($method,$data = [])
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

    $methods_to_log = self::METHODS_TO_LOG;
    if (array_key_exists( $method, $methods_to_log ) && is_array( $result['result'] ?? null ))
      $this->buffer[] = new Update(['update_id' => null, $methods_to_log[$method] => $result['result'] ], $this);

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
      // pre-checkout queries must be answered within 10 seconds, process them first
      // (buffer is not so large, we can avoid this for now)
      // usort($this->buffer, function (Update $a, Update $b) {
      //   return $b->hasTag('Checkout') <=> $a->hasTag('Checkout');
      // });
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
        // make array of pieces, separated by Uppercase letter
        // f.e. onSomeMethod => [ on, Some, Method ]
        $pieces = preg_split('/(?=[A-Z])/',$method);

        // method must start from 'on', and must have other parts
        if (array_shift($pieces) !== 'on' || count($pieces) == 0) continue;

        // count score to know, do we need to execute this method
        $score = 0;
        foreach ( $pieces as $piece)
          if (isset($tags[$piece]) && $tags[$piece])
            $score++;

        // if score doesn't match number of pieces, we skip this method
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

    $this->pause();
  }

  /**
   * Pause between loop iterations
   */
  protected function pause()
  {
    sleep(3);
  }

  /**
   * Display console message, if debug mode is on
   * @param $message
   * @return mixed
   */
  public function log( $message )
  {
    return $this->debug
           ? $this->console($message)
           : $message;
  }

  /**
   * Display console message
   * @param $message
   * @return mixed
   */
  public function console( $message )
  {
    $timestamp = date("Y-m-d H:i:s");
    $json = is_string($message) ? $message : json_encode($message, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    echo "\n[$timestamp] $json\n";
    return $message;
  }

  /**
   * @param Plugin\Base[] $plugins
   * @return $this
   */
  public function plug( array $plugins )
  {
    $this->plugins = $plugins;

    foreach ($this->plugins as $plugin)
    {
        if (method_exists($plugin, 'setBot'))
            call_user_func([$plugin, 'setBot'], $this);

        if (method_exists($plugin, 'init'))
            call_user_func([$plugin,'init']);
    }

    return $this;
  }

  public function plugins()
  {
      return $this->plugins;
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
    return $this->me = new User( $data );
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

  public function sendAction( $chat_id, $action, $options = [] )
  {
    $result = $this->_requestMultipart( 'sendChatAction', array_merge( [ 'chat_id'=>$chat_id, 'action'=>$action ], $options ));
    return new Message( $result );
  }

  public function forwardMessage( $chat_id, $from_chat_id, $message_id, $options = [] )
  {
    $result = $this->_request( 'forwardMessage', array_merge( [ 'chat_id'=>$chat_id, 'from_chat_id'=>$from_chat_id, 'message_id' => $message_id ], $options ));
    return new Message( $result );
  }

  /**
   * Send invoice
   * @see https://core.telegram.org/bots/api#sendinvoice
   * @param int|string $chat_id
   * @param string $title product name, 1-32 characters
   * @param string $description product description, 1-255 characters
   * @param string $payload bot-defined payload, 1-128 bytes, not displayed to the user
   * @param string $currency ISO 4217 currency code, or XTR for Telegram Stars
   * @param array $prices array of LabeledPrice arrays [['label'=>..., 'amount'=>...]], exactly one item for Stars
   * @param array $options other parameters (provider_token, photo_url, reply_markup, etc.)
   * @return Message
   */
  public function sendInvoice( $chat_id, $title, $description, $payload, $currency, array $prices, $options = [] )
  {
    $result = $this->_request( 'sendInvoice', array_merge( [
      'chat_id' => $chat_id, 'title' => $title, 'description' => $description,
      'payload' => $payload, 'currency' => $currency, 'prices' => $prices,
    ], $options ));
    return new Message( $result );
  }

  /**
   * Create a link for an invoice
   * @see https://core.telegram.org/bots/api#createinvoicelink
   * @return string|false
   */
  public function createInvoiceLink( $title, $description, $payload, $currency, array $prices, $options = [] )
  {
    return $this->_request( 'createInvoiceLink', array_merge( [
      'title' => $title, 'description' => $description,
      'payload' => $payload, 'currency' => $currency, 'prices' => $prices,
    ], $options ));
  }

  /**
   * Answer pre-checkout query, must be done within 10 seconds
   * @see https://core.telegram.org/bots/api#answerprecheckoutquery
   */
  public function answerPreCheckoutQuery( $pre_checkout_query_id, bool $ok = true, $error_message = null )
  {
    $data = ['pre_checkout_query_id' => $pre_checkout_query_id, 'ok' => $ok];
    if (!$ok && $error_message) $data['error_message'] = $error_message;
    return (bool) $this->_request( 'answerPreCheckoutQuery', $data );
  }

  /**
   * Answer shipping query
   * @see https://core.telegram.org/bots/api#answershippingquery
   */
  public function answerShippingQuery( $shipping_query_id, bool $ok = true, array $shipping_options = [], $error_message = null )
  {
    $data = ['shipping_query_id' => $shipping_query_id, 'ok' => $ok];
    if ($ok) $data['shipping_options'] = $shipping_options;
    elseif ($error_message) $data['error_message'] = $error_message;
    return (bool) $this->_request( 'answerShippingQuery', $data );
  }

  /**
   * Bot's current Telegram Stars balance
   * @see https://core.telegram.org/bots/api#getmystarbalance
   * @return StarAmount
   */
  public function getMyStarBalance()
  {
    return new StarAmount( $this->_request( 'getMyStarBalance' ) );
  }

  /**
   * Bot's Telegram Star transactions in chronological order
   * @see https://core.telegram.org/bots/api#getstartransactions
   * @return StarTransactions
   */
  public function getStarTransactions( $offset = 0, $limit = 100 )
  {
    return new StarTransactions( $this->_request( 'getStarTransactions', ['offset' => $offset, 'limit' => $limit] ) );
  }

  /**
   * Refund a successful payment in Telegram Stars
   * @see https://core.telegram.org/bots/api#refundstarpayment
   */
  public function refundStarPayment( $user_id, $telegram_payment_charge_id )
  {
    return (bool) $this->_request( 'refundStarPayment', ['user_id' => $user_id, 'telegram_payment_charge_id' => $telegram_payment_charge_id] );
  }

  public function id()
  {
    return $this->me->id();
  }

}