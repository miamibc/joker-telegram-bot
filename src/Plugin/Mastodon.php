<?php

namespace Joker\Plugin;

use GuzzleHttp\Client;
use Joker\Exception;
use Joker\Parser\Update;

class Mastodon extends Base
{

  private $client;

  public function __construct($options = [])
  {
    parent::__construct($options);

    if (!$token = getenv('MASTODON_API_TOKEN'))
      throw new Exception("Mastodon plugin requires MASTODON_API_TOKEN to be defined in .env file");

    $context = stream_context_create([
      'http' => [
        'method' => 'GET',
        'header'=> "Authorization: Bearer $token\r\n",
      ],
    ]);
    stream_set_timeout( $context, 10);
    stream_set_blocking( $context, false);
    $fp = fopen( getenv('MASTODON_HOST'). '/api/v1/streaming/public', 'r', false, $context);

    return;

    // initialize http client
    $this->client = new Client([
      'headers' => [
        'User-Agent' => 'joker_the_bot (+https://github.com/miamibc/joker-telegram-bot)',
      ],
    ]);

    $this->stream = $this->client->get(getenv('MASTODON_HOST') . '/api/v1/streaming/public', [
      'stream' => true,
      'timeout' => 15,
    ])->getBody()->detach();

  }

  public function onTimer( Update $update )
  {



  }

}