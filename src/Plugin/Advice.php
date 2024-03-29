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
 * Options:
 * - `random_time` (int, default 360) - seconds between random advices
 * - `random_ticks` (int, default 5)  - chat activity, number of messages per last minute
 * - `random_chance` (float, default .33) - random chance
 * - `random_delay` (int, default 5) - delay before message will be sent
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ConnectException;
use Joker\Helper\Tickometer;
use Joker\Helper\Timer;
use Joker\Parser\Update;

class Advice extends Base
{

  const TAGS_ENDPOINT  = 'https://fucking-great-advice.ru/api/v2/tags';
  const RANDOM_ENDPOINT  = 'https://fucking-great-advice.ru/api/v2/random-advices';
  const CATEGORY_ENDPOINT  = 'https://fucking-great-advice.ru/api/v2/random-advices-by-tag';

  protected $options = [
    'random_time'   => 60*60, // time condition (one advice per hour)
    'random_ticks'  => 5,     // tick condition (5 messages in last minute)
    'random_chance' => .33,   // random chance (33%)
    'random_delay'  => 5,     // random advice delay

    'description' => 'Fuckin great advices',
    'risk' => 'LOW. Nothing interestig can be extracted from API. See https://fucking-great-advice.ru for their terms.',
  ];

  private
    $tags = [],    // list of topics
    $advices = [], // advices caches
    $tickometer,   // tick-o-meter to track activity
    $timer,        // timer for delayed messaging
    $client,       // http client
    $last          // time of last random advice
  ;

  public function init()
  {
    // tick-o-meter to count text activity
    $this->tickometer = new Tickometer();

    $this->timer = new Timer();

    $this->last = time();

    // initialize http client
    $this->client = new Client([
      'timeout'  => 5.0,
      'headers' => [
        'Referer' => 'https://fucking-great-advice.ru/',
        'Accept' => 'application/json',
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:94.0) Gecko/20100101 Firefox/94.0',
      ],
    ]);

    // request information about tags
    try
    {
      $response = $this->client->get(self::TAGS_ENDPOINT);
      $body = json_decode($response->getBody(),true);
      foreach ($body['data'] as $item)
      {
        $this->tags[$item['alias']] = "{$item['title']}, {$item['advicesCount']} advices";
      }
    } catch (ConnectException $exception){
      /** nothing to do */
    }

  }

  public function onPublicText( Update $update )
  {

    $this->tickometer->tick();

    // answer to !advice command
    $text = $update->message()->text();
    $trigger = $text->trigger();
    if ( $trigger === 'advice')
    {
      $topic = $text->token(1);
      $advice = $this->getAdvice($topic);
      $update->answerMessage($advice);
      $this->last = time();
      $this->tickometer->clear();
      return false;
    }

    // random advice, if we pass some checks
    if (
      isset($this->tags['']) // tags is loaded
      && time()-$this->last         >= $this->getOption('random_time')
      && $this->tickometer->count() >= $this->getOption('random_ticks')
      && $this->randomFloat()       <= $this->getOption('random_chance')
    ){
      // send with delay
      $advice = $this->getAdvice();
      $this->timer->add( $this->getOption('random_delay'), function () use ($update, $advice) {
        $update->answerMessage( $advice );
      });
      $this->last = time();
      $this->tickometer->clear();
    }

  }

  public function onEmpty( Update $update )
  {
    $this->timer->run();
  }

  /**
   * Generate random float number
   * @param int|float $min
   * @param int|float $max
   *
   * @return float
   */
  public function randomFloat($min = 0, $max = 1)
  {
    return $min + mt_rand() / mt_getrandmax() * ($max - $min);
  }

  /**
   * @param string $topic
   *
   * @return string
   */
  public function getAdvice($topic = '')
  {

    // topic specified, but not exists in available topics
    if ($topic && !isset($this->tags[$topic]))
    {
      // answer with list of topics
      $answer = [ "Wrong category. Category can be:" ];
      foreach ( $this->tags as $tag => $description)
      {
        $answer[] = "- $tag ($description)";
      }
      return implode("\n",$answer);
    }

    // load few advices from sever
    if (!isset( $this->advices[$topic] ) || empty( $this->advices[$topic]))
    {
      try
      {
        $request = empty($topic)
          ? $this->client->get(self::RANDOM_ENDPOINT)
          : $this->client->get(self::CATEGORY_ENDPOINT,['query' => ['tag' => $topic]]);
        $body = json_decode($request->getBody(),true);
      }
      catch (ConnectException $e){
        /* nothing to do */
      }
      // no advices came in
      if (!isset($body['data']) || empty( $body['data']))
      {
        return 'Looks like we have no advices at the moment. Try again later.';
      }

      // shuffle and remember advices
      shuffle($body['data']);
      $this->advices[$topic] = $body['data'];

    }

    // get one element from advices
    $advice = array_shift( $this->advices[$topic] );
    return $advice['text'];

  }


}