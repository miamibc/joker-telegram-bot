<?php
/**
 * Random joke from Anekdot.ru plugin for Joker
 *
 * Ask random joke, or search by id or text:
 *
 * !anek
 * !anek 833334
 * !anek блондинка
 *
 * Bot will answer you something like
 *
 * !anek #833334
 * Теперь в Евросоюзе 1GB свободного места.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use DiDom\Document;
use Joker\Parser\Update;

class Anek extends Base
{

  const RANDOM_ENDPOINT  = 'https://pda.anekdot.ru/random/anekdot/';
  const SEARCH_ENDPOINT  = 'https://pda.anekdot.ru/search/';
  // ?query=до+депа&ch[j]=on&ch[s]=on&mode=phrase&xcnt=100&maxlen=0&order=0
  const GETBYID_ENDPOINT = 'https://pda.anekdot.ru/id/';
  // https://pda.anekdot.ru/id/789752/

  private $random_jokes = [];

  protected $options = [
    'description' => 'Random joke from Anekdot.ru',
    'risk' => 'MEDIUM. Anonymous request\'s text can be visible in anekdot.ru access logs by site administrator',
  ];

  public function onPublicText( Update $update )
  {

    $text = $update->message()->text();

    if ( $text->trigger() !== 'anek') return;

    $trigger = trim( $text->trigger() );
    $query   = trim( $text->token(1) );

    if (empty( $query ))
    {
      // random joke
      $joke = $this->getRandomJoke();
    }
    elseif ( is_numeric($query) || substr($query, 0, 1) === '#')
    {
      // get by id
      $id = is_numeric($query) ? $query : substr($query, 1);
      $joke = $this->getJokeById( $id );
    }
    else
    {
      // search
      $joke = $this->getSearchJoke( $query );
    }

    if (!$joke) $joke = "Joke not found :(";
    $update->answerMessage( "!$trigger $joke");
    return false;

  }

  /**
   * Get one joke from random_jokes pool.
   * If pool is empty, loads new page of random joke from the site
   * @return string A joke
   */
  private function getRandomJoke()
  {
    if (!count($this->random_jokes))
    {
      $content = file_get_contents(self::RANDOM_ENDPOINT);
      $this->random_jokes = $this->parseJokes( $content );
    }
    return array_pop($this->random_jokes);
  }

  /**
   * Get joke by ID
   * @param $id
   *
   * @return false|string
   * @throws \DiDom\Exceptions\InvalidSelectorException
   */
  private function getJokeById( $id )
  {
    $content = file_get_contents(self::GETBYID_ENDPOINT . $id);

    // suddenly, we can't use ::parseJkokes() method, because template is a bit different here
    $document = new Document($content);
    if (!$text = $document->first('.text')) return false;
    $text = strtr( $text->innerHtml(), ['<br>'=>"\n", '<br />'=>"\n"] );
    $text = strip_tags( $text );
    $text = html_entity_decode( $text, ENT_QUOTES );
    return "#$id\n" . trim( $text );
  }

  /**
   * Search on site
   *
   * @param string $query text or joke id
   * @return string
   */
  private function getSearchJoke($query)
  {
    // if query is number, we can search by id. Just ensure we removed # from beginning
    if (preg_match('@#(\d+)$@',  $query, $matches)) $query = $matches[1];

    //ch[j]=on&ch[s]=on&mode=phrase&xcnt=100&maxlen=0&order=0
    $content = file_get_contents(self::SEARCH_ENDPOINT . '?' . http_build_query([
      'query'=>$query,
      'ch' => [ 'j'=>'on' , 's' => 'on' ],
      'mode' => 'phrase',
      'xcnt' => 100,
      'maxlen' => 0,
      'order' => 0,
    ]));
    $jokes = $this->parseJokes( $content );

    if (!count($jokes)) return false;
    return $jokes[ mt_rand(0, count($jokes)-1)];
  }

  /**
   * Parse page content for jokes and their IDs
   * @param $content
   *
   * @return array
   */
  private function parseJokes( $content )
  {

    $result = [];
    $html = new Document($content);

    foreach ( $html->find('div[data-id]') as $item)
    {
      if (!$text = $item->find('div.text')) continue;
      $id = $item->{"data-id"};
      $text = strtr( $text[0]->innerHtml(), ['<br>'=>"\n", '<br />'=>"\n"] );
      $text = strip_tags( $text );
      $text = html_entity_decode( $text, ENT_QUOTES );
      $result[] = "#$id\n" . trim( $text );
    }
    return $result;
  }

}