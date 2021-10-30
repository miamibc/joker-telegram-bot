<?php
/**
 * Fuckin great advice plugin for Joker
 * @see https://fucking-great-advice.ru/
 *
 * You can ask:
 *   !advice
 *      bot answers with random advice
 *   !advice topic
 *      bot answers with random advice from topic
 *   !advice wrongtopic
 *      bot will answer with list of proper topics
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use GuzzleHttp\Client;
use Joker\Parser\Update;

class Advice extends Base
{

  const TAGS_ENDPOINT  = 'https://fucking-great-advice.ru/api/v2/tags';
  const RANDOM_ENDPOINT  = 'https://fucking-great-advice.ru/api/v2/random-advices';
  const CATEGORY_ENDPOINT  = 'https://fucking-great-advice.ru/api/v2/random-advices-by-tag';

  private $tags = [];
  private $advices = [];
  private $client;

  public function __construct($options = [])
  {
    parent::__construct($options);

    // initialize http client
    $this->client = new Client([
      'timeout'  => 2.0,
      'headers' => [
        'Referer' => 'https://fucking-great-advice.ru/',
        'Accept' => 'application/json',
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:94.0) Gecko/20100101 Firefox/94.0',
      ],
    ]);

    // request information about tags
    $response = $this->client->get(self::TAGS_ENDPOINT);
    $body = json_decode($response->getBody(), true);
    foreach ($body['data'] as $item)
    {
      $this->tags[ $item['alias'] ] ="{$item['title']}, {$item['advicesCount']} advices";
    }

  }

  public function onPublicText( Update $update )
  {

    $text = $update->message()->text();

    $trigger = $text->trigger();
    $query   = trim( $text->token(1) );

    if ( $trigger !== 'advice') return;

    // if query exists, but not found in tags
    if ($query && !isset($this->tags[$query]))
    {
      // answer with all available tags
      $answer = [
        "To get advice of some category, please write one of category after !$trigger",
        "Category can be:",
      ];
      foreach ( $this->tags as $tag => $description)
      {
        $answer[] = "- $tag ($description)";
      }
      $update->answerMessage(implode("\n",$answer));
      return false;
    }

    // if no advices of [query] loaded, load them
    if (!isset( $this->advices[$query] ) || empty( $this->advices[$query]))
    {
      $request = empty($query)
        ? $this->client->get(self::RANDOM_ENDPOINT)
        : $this->client->get(self::CATEGORY_ENDPOINT, ['query'=> ['tag' => $query]])
      ;
      $body = json_decode($request->getBody(),true);
      $this->advices[$query] = array_rand( $body['data'] );
    }

    // get one element from advices
    $advice = array_shift( $this->advices[$query] );
    $update->answerMessage( $advice['text'] );
    return false;

  }


}