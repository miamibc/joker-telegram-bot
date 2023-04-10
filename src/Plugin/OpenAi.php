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
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use GuzzleHttp\Client;
use Joker\Parser\Update;

class OpenAi extends Base
{

  private $client;
  private $context = [];

  public function __construct(array $options = [])
  {
    parent::__construct($options);

    $this->client = new Client([
      'base_uri' => 'https://api.openai.com/v1/completions',
      'headers' => [
        'Content-Type' => 'application/json',
        'Authorization' => 'Bearer ' . $this->getOption('api_key', getenv('OPENAI_API_KEY')),
      ],
      'timeout' => 20,
    ]);
  }

  public function onPublicText(Update $update)
  {
    $context_size = $this->getOption('context_size', 9);
    $this->context[] = "{$update->message()->from()->name()}: {$update->message()->text()}";
    if (count($this->context) > $context_size) $this->context = array_slice( $this->context, -$context_size);

    $text = (string)$update->message()->text();

    // answer to texts with Joker
    if (!preg_match('/\b(joker|джокер|jok|джок)\b/ui', $text)) return;

    // answer only to premium users
    // if (!$update->message()->from()->is_premium()) return;

    $name = $this->getOption('name', 'Joker');
    $bio  = $this->getOption('bio' , 'Joker is a chatbot that reluctantly answers questions with sarcastic responses');

    $response = $this->client->post('/v1/completions', ['json' => [
      "model" => $this->getOption('model', 'text-davinci-003' ),
      "prompt" => $prompt = "$bio\n\n" . implode("\n", $this->context ) . "\n$name:",
      "temperature" => $this->getOption('temperature', 0.5),
      "max_tokens" => $this->getOption('max_tokens',500),
      "top_p" => 0.3,
      "frequency_penalty" => 0.5,
      "presence_penalty" => 0.0,
    ]])->getBody()->getContents();

    $response = json_decode( $response );
    if (!$answer = $response->choices[0]->text ?? null) return;

    // debug
    $update->bot()->log([
      'prompt' => $prompt,
      'response' => $response,
      'answer' => $answer,
    ]);

    // answer
    $update->answerMessage($answer);

    // save context
    $this->context[] = "";
    $this->context[] = "{$update->message()->from()->name()}: {$update->message()->text()}";
    $this->context[] = "{$name}: {$answer}";
    if (count($this->context) > $context_size) $this->context = array_slice( $this->context, -$context_size);

    return false;

  }

}