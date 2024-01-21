<?php
/**
 * QuoteInline plugin for Joker
 *
 * Type bot @username with text to search in jokes
 * Then select any joke from list, this will post a joke via the bot
 *
 * Configuration options:
 * - `trigger` (string, required) for now this plugin allows to serve only one file with jokes, type it's name here
 * - `limit`   (integer, optional, default 5) maximum number of jokes to display in suggestion block
 * - `length`  (integer, optional, default 80) length of search results in pop-up menu, about 80 symbols are visible in Telegram Desktop @ 2024
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use RedBeanPHP\OODBBean;
use RedBeanPHP\R;

class QuoteInline extends Base
{

  protected $options = [
    'trigger' => 'tg',
    'limit' => 5,
    'length' => 80,

    'description' => 'Inline quote',
    'risk' => "LOW. You can accidentially send request to QuoteInline by typing @bot_name and some texts. We don't store and share this information",
  ];

  public function onInline( Update $update )
  {
    if (!$trigger = $this->getOption('trigger')) return;
    $limit = $this->getOption('limit');
    $query  = '%' . strtr( mb_strtolower(trim($update->inline_query()->query())), [' ' => '%']) . '%';

    // if only %, do nothing
    if (!preg_match('/[^%]/', $query)) return;

    // no results - do nothing
    if (!$jokes = R::find('joke', " trigger = ? AND search LIKE ? ORDER BY random() LIMIT ? ", [ $trigger, $query, $limit ] )) return;

    // $update->bot()->log("Query: '$query' => " . count($jokes) . " results");

    $highlighted = $update->inline_query()->query();
    $length = $this->getOption('length', 80);

    $update->inline_query()->answer([
      'results' => array_values(array_map(function (OODBBean $item) use ($highlighted, $length){
        return [
          'id' => md5(mt_rand(0,1000000) .'#' . $item->id),
          'type' => 'article',
          'title' => "{$item->trigger} #{$item->id}:",
          'description' => $this->shortenStringHighlighted( $item->joke, $highlighted, $length),
          'input_message_content' => [
            'message_text' => "{$item->trigger} #{$item->id}: {$item->joke}",
          ],
        ];
      }, $jokes )),
      'cache_time' => 1,
      'is_personal' => true,
    ]);
    return false;
  }


  /**
   * Shortens a string and highlights specific words within the string.
   *
   * @param string $string      The string to shorten.
   * @param string $highlighted The word(s) to highlight within the string.
   * @param int    $length      The maximum length of the shortened string.
   *
   * @return string The shortened string with highlighted words.
   */
  public function shortenStringHighlighted( $string, $highlighted = "", $length = 80 )
  {
    // trim string
    $string = trim($string);

    // sort highlighting array by length of the word, longer word in the top
    $highlighted = explode(' ', $highlighted);
    usort($highlighted, function ($w1,$w2){
      return mb_strlen($w1) < mb_strlen($w2);
    });
    foreach ($highlighted as $word)
    {
      // find this word in a string
      $startpos = mb_stripos( $string, $word );

      // nit found? next word
      if ($startpos === false) continue;

      if (mb_strlen($string) > $length)
      {

        $startpos -= floor( ($length-mb_strlen($word))/2 );

        // fix start position
        if ($startpos < 0) $startpos = 0;
        elseif ($startpos+$length > mb_strlen($string)) $startpos = mb_strlen($string)-$length;
        else true;

        $string =  ($startpos > 0?'…':'') .
          mb_substr($string, $startpos, $length) .
          ($startpos+$length < mb_strlen($string) ?'…':'');
      }
      return $string;
    }

    // fallback, just cutted string
    return mb_substr($string, 0, $length);
  }
}