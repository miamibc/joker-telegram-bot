<?php

/**
 * Mastodon plugin for Joker
 *
 * Enable live translation of updates from Mastodon on your Telegram channel by typing:
 *   !mastodon abcd       (abcd is a message you want to search in the updates)
 *
 * To disable translation, type:
 *   !mastodon off
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Exception;
use Joker\Parser\Update;

class Mastodon extends Base
{

  private $client;
  private $subscribers = [];

  protected $options = [
    'description' => 'Mastodon integration plugin',
    'risk' => 'LOW. Nothing is shared with Mastodon APIs, we only read information from there.',
  ];

  public function init()
  {
    if (!$token = getenv('MASTODON_API_TOKEN'))
      throw new Exception("Mastodon plugin requires MASTODON_API_TOKEN to be defined in .env file");

    $context = stream_context_create([
      'http' => [
        'method' => 'GET',
        'header'=> "Authorization: Bearer $token\r\n",
      ],
    ]);
    $this->client = fopen( getenv('MASTODON_HOST'). '/api/v1/streaming/public', 'r', false, $context);
    // stream_set_timeout( $this->client, 10);
    stream_set_blocking( $this->client, false);
  }

  public function onPublicText( Update  $update )
  {
    if ($update->message()->text()->trigger() != 'mastodon') return;

    $status = $update->message()->text()->token(1);

    if ($status === 'off')
    {
      unset($this->subscribers[ $update->message()->chat()->id()]);
      $update->replyMessage("Mastodon translation stopped");
    }
    else
    {
      $this->subscribers[ $update->message()->chat()->id()] = $status;
      $update->replyMessage("Mastodon translation started, word to search: *$status*");
    }
    return false;
  }

  public function onTimer( Update $update )
  {
    do
    {
      if (($event = fgets($this->client)) === false) break;
      if (($data = fgets($this->client)) === false) break;

      if (substr($event,0,7) !== 'event: ') continue;
      if (substr($data,0,6) !== 'data: ') continue;

      $event = trim(substr($event,7));
      $data  = json_decode(trim(substr($data,6)));

      // listen for only events listed here
      if (!in_array($event,['update','status.update'])) continue;

      // leave only allowed tags in a message
      $content = strip_tags($data->content, ['b','i','em','u','ins','s','strike','del','b','a','code','pre']);

      // send notifications to subscribed channels
      foreach ( $this->subscribers as $chat_id => $string)
        if (stripos($content, $string) !== false)
          $update->bot()->sendMessage( $chat_id, "&lt;{$data->account->username}&gt; {$content}" , ['parse_mode' => 'HTML']);

      // echo("\n>>> $event ".json_encode($data));

    } while(true);
  }

}