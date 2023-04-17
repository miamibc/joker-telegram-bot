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

  public function __construct(array $options = [])
  {
    parent::__construct($options);

    if (!$api_key = $this->getOption('api_key', getenv('OPENAI_API_KEY')))
      throw new Exception('API key required to start OpenAI plugin, please define OPENAI_API_KEY env variable, or `api_key` parameter');

    $this->client = new Client([
      'base_uri' => 'https://api.openai.com/',
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $api_key,
      ],
      'timeout' => 20,
    ]);
  }

  public function onPublicReply(Update $update)
  {
    $name = $this->getOption('name', 'Joker');
    $bio = $this->getOption('bio', 'Joker is a chatbot that reluctantly answers questions with sarcastic responses');

    // build prompt:
    // start from current message and go up in history,
    // append these messages to the prompt
    // and finally append bio
    $prompt = "";
    $message = $update->message();
    $joker_was_here = false;
    do {
      $prompt = "{$message->from()->name()}: {$message->text()}\n$prompt";
      if ($message->from()->id() == $update->bot()->id()) $joker_was_here = true;
      if (!$replied_to = $message->reply_to_message()) break;
    } while( $message = $this->context[$replied_to->id()] ?? false );

    // no joker in discussion, or no prompt at all, no need to answer
    if (!$joker_was_here || !$prompt = trim($prompt)) return;

    // answer only to premium users
    if ( $this->getOption('premium_only') && !$update->message()->from()->is_premium()) return;

    if (mb_strlen($prompt) > 1000)
    {
      $update->replyMessage("Многовато вопросов, сорян, закрываем лавочку :p");
      return false;
    }

    // add bio and final message
    $prompt = "$bio\n$prompt\n$name:";

    $response = $this->client->post('/v1/completions', ['json' => [
      "model" => $this->getOption('model', 'text-davinci-003'),
      "prompt" => $prompt,
      "temperature" => $this->getOption('temperature', 0.5),
      "max_tokens" => $this->getOption('max_tokens', 500),
      "top_p" => 0.3,
      "frequency_penalty" => 0.5,
      "presence_penalty" => 0.0,
    ]])->getBody()->getContents();

    $response = json_decode($response);
    $update->bot()->log($response);

    // no answer, nothing to do
    if (!$answer = $response->choices[0]->text ?? null) return;

    // answer
    $reply = $update->replyMessage($answer);

    // save context
    $this->context[$update->message()->id()] = $update->message();
    $this->context[$reply->id()] = $reply;

    return false;

  }

  public function onPublicText(Update $update)
  {

    $text = (string)$update->message()->text();

    // answer to texts with Joker
    if (!preg_match('/\b(joker|джокер|jok|джок)\b/ui', $text)) return;

    // answer only to premium users
    if ( $this->getOption('premium_only') && !$update->message()->from()->is_premium()) return;

    $name = $this->getOption('name', 'Joker');
    $bio  = $this->getOption('bio' , 'Joker is a chatbot that reluctantly answers questions with sarcastic responses');
    $prompt = "$bio\n{$update->message()->from()->name()}: {$update->message()->text()}\n$name:";

    $response = $this->client->post('/v1/completions', ['json' => [
      "model" => $this->getOption('model', 'text-davinci-003'),
      "prompt" => $prompt,
      "temperature" => $this->getOption('temperature', 0.5),
      "max_tokens" => $this->getOption('max_tokens', 500),
      "top_p" => 0.3,
      "frequency_penalty" => 0.5,
      "presence_penalty" => 0.0,
    ]])->getBody()->getContents();

    $response = json_decode($response);
    $update->bot()->log($response);

    // no answer, nothing to do
    if (!$answer = $response->choices[0]->text ?? null) return;

    // answer
    $reply = $update->replyMessage($answer);

    // save context
    $this->context[$update->message()->id()] = $update->message();
    $this->context[$reply->id()] = $reply;

    return false;

  }

}