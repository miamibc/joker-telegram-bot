<?php

/**
 * OpenAI Plugin
 *
 * Add chatting ability to your bot with help of [OpenAI](https://platform.openai.com/)
 *
 * To start plugin, you need to have account in [OpenAI platform](https://platform.openai.com/). Insert API key to the .env file, like this:
 *
 * - `OPENAI_API_KEY` your api token
 *
 * Or provide `api_key` initialization parameter.
 *
 * Here are all parameters you can customize:
 * - `max_content_length` (integer, optional, default 1000) - maximum length of the content
 * - `premium_only` (bool, optional, default false) - answer only to premium accounts
 * - `api_key` (string, optional, default from env variable OPENAI_API_KEY) - API key from OpenAI
 * - `bio` (array | string, optional, default 'Joker is a chatbot that reluctantly answers questions with sarcastic responses') - few words about your bot, will be always placed at the top of OpenAI context
 * - `model` (string, optional, default 'gpt-4') - OpenAI setting, model to use in OpenAI API request
 * - `temperature` (integer, optional, default 0.5) - OpenAI setting, randomness of the bot answers
 * - `max_tokens` (integer, optional, default 500) - OpenAI setting, maximum size of the answer (+- number of english words)
 * - `top_p` (decimal, optional, default 0.3) - OpenAI setting
 * - `frequency_penalty` (decimal, optional, default 0.5) - OpenAI setting
 * - `presence_penalty` (decimal, optional, default 0.0) - OpenAI setting
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use GuzzleHttp\Client;
use Joker\Exception;
use Joker\Helper\Strings;
use Joker\Parser\Update;

class OpenAi extends Base
{

  private $client;
  private $context = [];
  private $started;
  private $stats = [
    'requests_count' => 0,
    'last_activity' => 0,
    'prompt_tokens' => 0,
    'completion_tokens' => 0,
    'total_tokens' => 0,
  ];

  protected $options = [
    // joker options
    'max_content_length' => 1000, // maximum size of text, passed to the OpenAI api
    'premium_only' => false,      // answeronly to premium users
    'bio' => 'Joker is a chatbot that reluctantly answers questions with sarcastic responses', // bot biography, used in system message for OpenAI

    // OpenAI parameters
    'model' => 'gpt-4',
    'temperature' =>  0.5,
    'max_tokens' =>  500,
    'top_p' => 0.3,
    'frequency_penalty' => 0.5,
    'presence_penalty' => 0.0,

    // privacy information
    'description' => 'Adds talking ability to your bot',
    'risk' => 'Context [your question and a dialogue in replies] is sent to OpenAI API and processed there. See OpenAI article https://help.openai.com/en/articles/6837156-terms-of-use',
  ];

  public function init()
  {
    if (!$api_key = $this->getOption('api_key', getenv('OPENAI_API_KEY')))
      throw new Exception('API key required to start OpenAI plugin, please define OPENAI_API_KEY env variable, or `api_key` parameter');

    $this->client = new Client([
      'base_uri' => 'https://api.openai.com/',
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => "Bearer $api_key",
      ],
      'timeout' => 20,
    ]);

    $this->started = time();
  }

  public function onText(Update $update)
  {

    $text = $update->message()->text();

    // some commands
    if ($text->trigger() == 'openai')
    {
      switch($text->token(1,1))
      {
        case 'parameters':
        case 'params':
          $update->replyMessage(implode(PHP_EOL, [
            "model => " . $this->getOption("model"),
            "temperature => " . $this->getOption("temperature"),
            "max_tokens => " . $this->getOption("max_tokens"),
            "top_p => " . $this->getOption("top_p"),
            "frequency_penalty => " . $this->getOption("frequency_penalty"),
            "presence_penalty => " . $this->getOption("presence_penalty"),
          ]));
          return false;
        case 'usage':
        case 'stats':
          $update->replyMessage(implode(PHP_EOL, [
            "started => " . Strings::timeElapsed(date('Y-m-d', $this->started)),
            "last_activity => " . ($this->stats['last_activity'] ? Strings::diffTimeInWords($this->stats['last_activity'], time()).' ago' : 'Never'),
            "requests_count => " . $this->stats['requests_count'],
            "prompt_tokens => " . $this->stats['prompt_tokens'],
            "completion_tokens => " . $this->stats['completion_tokens'],
            "total_tokens => " . $this->stats['total_tokens'],
          ]));
          return false;
      }

    }

    // answer only to premium users
    if ( $this->getOption('premium_only') && !$update->message()->from()->is_premium()) return;

    $bio = array_map(function( $item ){
      return [
        'role' => 'system',
        "content" => $item,
      ];
    }, is_array($b = $this->getOption('bio')) ? $b : [$b]);

    // look up the conversation: start from current message and go up in history
    $reply = false;
    $message = $update->message();
    do
    {
      // text has special word -> reply
      $text = (string) $message->text();
      if (preg_match('/\b(joker|джокер|jok|джок)\b/ui', $text)) $reply = true;

      // joker is in conversation -> reply
      if ($is_me = $message->from()->id() == $update->bot()->id()) $reply = true;

      // add to conversation
      $messages[] = [
        'role'    => $is_me ? "assistant" : "user",
        'name'    => $this->prepareName($message->from()->name() ),
        "content" => $text,
      ];

      // not a reply -> break
      if (!$replied_to = $message->reply_to_message()) break;
    }
    while( $message = $this->context[$replied_to->id()] ?? false );

    // no need to reply? okay ignore
    if (!$reply) return;

    // just in case
    if (empty($messages)) return;

    // combine bio with messages in reverse order
    $messages = array_merge($bio, array_reverse( $messages ));

    // debug
    $this->bot()->log( $messages );

    // check size of request
    $size = array_sum(array_map(function ($item){
      return mb_strlen( $item['content']);
    }, $messages));
    if ($size > $this->getOption('max_content_length'))
    {
      $update->replyMessage("Многовато вопросов, сорян, закрываем лавочку :p");
      // ideas:
      // Многовато вопросов, сорян, закрываем лавочку :p"
      // Многобукв! Автор выпей йаду :p
      return false;
    }

    $update->message()->chat()->sendAction(Update::ACTION_TYPING);

    $response = $this->client->post('/v1/chat/completions', ['json' => [
      'model' => $this->getOption('model'),
      'messages' => $messages,
      'temperature' => $this->getOption('temperature'),
      'max_tokens' => $this->getOption('max_tokens'),
      'top_p' => $this->getOption('top_p'),
      'frequency_penalty' => $this->getOption('frequency_penalty'),
      'presence_penalty' => $this->getOption('presence_penalty'),
    ]])->getBody()->getContents();

    $response = json_decode($response);

    // debug
    $update->bot()->log($response);

    // no answer, nothing to do
    if (!isset($response->choices[0]->message->content)) return;
    if (!$answer = $response->choices[0]->message->content ?? null) return;

    // answer
    $reply = $update->replyMessage($answer);

    // save both messages to context
    $this->context[$update->message()->id()] = $update->message();
    $this->context[$reply->id()] = $reply;

    // save stats
    $this->stats['requests_count']++;
    $this->stats['last_activity'] = time();
    $this->stats['prompt_tokens'] += $response->usage->prompt_tokens;
    $this->stats['completion_tokens'] += $response->usage->completion_tokens;
    $this->stats['total_tokens'] += $response->usage->total_tokens;

    return false;

  }

  private function prepareName( $name ) : string
  {
    return substr( preg_replace('@[^a-zA-Z0-9_-]@', '', $name), 0, 64);
  }

}