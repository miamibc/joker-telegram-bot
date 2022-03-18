<?php
/**
 * Random joke from Anekdot.ru plugin for Joker
 *
 * Ask random joke, or search by id or text:
 *   !anek
 *   !anek 1234
 *   !anek scuko blya jjosh
 *
 * Bot will answer you with joke from Anekdot.ru
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Anek extends Base
{

  const RANDOM_ENDPOINT  = 'https://pda.anekdot.ru/random/anekdot/';
  const SEARCH_ENDPOINT  = 'https://pda.anekdot.ru/search/';
  // ?query=до+депа&ch[j]=on&ch[s]=on&mode=phrase&xcnt=100&maxlen=0&order=0
  const GETBYID_ENDPOINT = 'https://pda.anekdot.ru/id/';
  // https://pda.anekdot.ru/id/789752/

  private $random_jokes = [];

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
    else
    {
      // search
      $joke = $this->getSearchJoke( $query );
    }

    if (!$joke) $joke = "Joke not found :(";
    $update->answerMessage( "$trigger $joke");
    return false;

  }

  /**
   * Get one joke from random_jokes pool.
   * If pool is empty, loads from bash.im
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
   * Performas sarch on bash.im
   *
   * @param string $query text or joke id
   * @return string
   */
  private function getSearchJoke($query)
  {
    // if query is number, we can search by id. Just ensure we removed # from beginning
    if (preg_match('@#(\d+)$@',  $query, $matches)) $query = $matches[1];

    $content = file_get_contents(self::SEARCH_ENDPOINT . '?' . http_build_query(['text'=>$query]));
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

    preg_match_all('@<article class="quote" data-quote="(\d+)">[\s\S]+<div class="quote__body">[\s\n]+(.*)$@imU', $content, $matches, PREG_SET_ORDER);

    $result = [];
    foreach ($matches as $match)
    {
      $id   = $match[1];
      $text = $match[2];
      $text = strtr( $text, ['<br>'=>"\n", '<br />'=>"\n"] );
      $text = html_entity_decode( $text, ENT_QUOTES );
      $result[] = "#$id\n" . trim( $text );
    }
    return $result;
  }

}