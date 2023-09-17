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
 *
 * - `api_key` (string, optional, default from env variable OPENAI_API_KEY) - API key from OpenAI
 * - `model` (string, optional, default 'text-davinci-003') - model to use in OpenAI API request
 * - `context_size` (integer, optional, default 9) - context size
 * - `name` (string, optional, default Joker) - name of the bot, that will be used in context generating
 * - `bio` (string, optional, default 'Joker is a chatbot that reluctantly answers questions with sarcastic responses') - few words about your bot, will be always placed at the top of OpenAI context
 * - `temperature` (integer, optional, default 0.5) - randomness of the bot answers
 * - `max_tokens` (integer, optional, default 500) - maximum size of the answer (+- number of english words)
 * - `max_context_length` (bool, optional, default 1000) - maximum length of the context
 * - `premium_only` (bool, optional, default false) - answer only to premium accounts
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use GuzzleHttp\Client;
use Joker\Exception;
use Joker\Parser\Update;

class OpenAi extends Base
{

  private $client;
  private $context = [];

  protected $options = [
    'model' => 'gpt-4',
    'bio' => 'Joker is a chatbot that reluctantly answers questions with sarcastic responses',
    'max_context_length' => 1000,

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
  }

  public function onPublicReply(Update $update)
  {
    $model = $this->getOption('model');
    $bio = array_map(function( $item ){
      return [
        'role' => 'system',
        "content" => $item,
      ];
    }, is_array($b = $this->getOption('bio')) ? $b : [$b]);

    // look up the conversation: start from current message and go up in history
    $me_was_here = false;
    $message = $update->message();
    do
    {
      // detect joker was in conversation
      if ($is_me = $message->from()->id() == $update->bot()->id()) $me_was_here = true;

      // build conversation
      $messages[] = [
        'role' => $is_me ? "assistant" : "user",
        'name' => $this->prepareName($message->from()->name() ),
        "content" => (string) $message->text(),
      ];

      // if not a reply, break
      if (!$replied_to = $message->reply_to_message()) break;
    }
    while( $message = $this->context[$replied_to->id()] ?? false );

    // no joker in conversation, or no messages found, no need to answer
    if (!$me_was_here || empty($messages)) return;

    // answer only to premium users
    if ( $this->getOption('premium_only') && !$update->message()->from()->is_premium()) return;

    $messages = array_merge($bio, array_reverse( $messages ));

    // $this->bot()->log( $messages );

    //    if (mb_strlen($prompt) > $this->getOption('max_context_length', 1000))
    //    {
    //      $update->replyMessage("Многовато вопросов, сорян, закрываем лавочку :p");
    //      return false;
    //    }

    $response = $this->client->post('/v1/chat/completions', ['json' => [
      "model" => $model,
      "messages" => $messages,
      "temperature" => $this->getOption('temperature', 0.5),
      "max_tokens" => $this->getOption('max_tokens', 500),
      "top_p" => 0.3,
      "frequency_penalty" => 0.5,
      "presence_penalty" => 0.0,
    ]])->getBody()->getContents();

    $response = json_decode($response);

    // $update->bot()->log($response);

    // no answer, nothing to do
    if (!$answer = $response->choices[0]->message->content ?? null) return;

    // answer
    $reply = $update->replyMessage($answer);

    // save context
    $this->context[$update->message()->id()] = $update->message();
    $this->context[$reply->id()] = $reply;

    return false;

  }

  public function onPublicText(Update $update)
  {

    $model = $this->getOption('model');
    $bio = array_map(function( $item ){
      return [
        'role' => 'system',
        "content" => $item,
      ];
    }, is_array($b = $this->getOption('bio')) ? $b : [$b]);

    $text = (string)$update->message()->text();

    // answer to texts with Joker
    if (!preg_match('/\b(joker|джокер|jok|джок)\b/ui', $text)) return;

    // answer only to premium users
    if ($this->getOption('premium_only') && !$update->message()->from()->is_premium()) return;

    $messages = array_merge( $bio, [[
      "role" => "user",
      'name' => $this->prepareName( $update->message()->from()->name() ),
      "content" => $text,
    ]]);

    // $this->bot()->log( $messages );

    // check size of request
    $size = array_sum(array_map(function ($item){
      return mb_strlen( $item['content']);
    }, $messages));
    if ($size > $this->getOption('max_context_length', 1000))
    {
      $update->replyMessage("Многобукв! Автор выпей йаду :p");
      return false;
    }

    $response = $this->client->post('/v1/chat/completions', ['json' => [
      "model" => $model,
      "messages" => $messages,
      "temperature" => $this->getOption('temperature', 0.5),
      "max_tokens" => $this->getOption('max_tokens', 500),
      "top_p" => 0.3,
      "frequency_penalty" => 0.5,
      "presence_penalty" => 0.0,
    ]])->getBody()->getContents();

    $response = json_decode($response);

    $update->bot()->log($response);

    // no answer, nothing to do
    if (!$answer = $response->choices[0]->message->content ?? null) return;

    // answer
    $reply = $update->replyMessage($answer);

    // save context
    $this->context[$update->message()->id()] = $update->message();
    $this->context[$reply->id()] = $reply;

    return false;

  }

  private function prepareName( $name ) : string
  {
    return substr( preg_replace('@[^a-zA-Z0-9_-]@', '', $name), 0, 64);
  }

}