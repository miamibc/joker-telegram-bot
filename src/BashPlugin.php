<?php
/**
 * Random joke from Bash.im, plugin for Joker
 *
 * Ask random joke, or search by id or text:
 *   !bash
 *   !bash 1234
 *   !bash scuko blya jjosh
 *
 * Bot will answer you with joke from bash
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class BashPlugin extends Plugin
{

  const RANDOM_ENDPOINT  = 'https://bash.im/random';
  const SEARCH_ENDPOINT  = 'https://bash.im/search';

  private $random_jokes = [];

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/bash|!bash)\b(.*)?$@ui', $text, $matches)) return;

    $trigger = trim( $matches[1] );
    $query   = trim( $matches[2] );

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
    $event->answerMessage( "$trigger $joke");
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