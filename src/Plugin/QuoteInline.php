<?php
/**
 * QuoteInline plugin for Joker
 *
 * Type bot @username with text to search in jokes
 * Then select any joke from list, this will post a joke via the bot
 *
 * Configuration options:
 * - `dir`     (string, optional, default data/jokes) directory with jokes
 * - `limit`   (integer, optional, default 10) maximum number of jokes to display in suggestion block
 * - `trigger` (string, required) for now this plugin allows to serve only one file with jokes, type it's name here
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class QuoteInline extends Base
{

  protected $options = [
    'dir' => 'data/jokes',
    'limit' => 10,
  ];

  public function onInline( Update $update )
  {
    $query = $update->inline_query()->query();
    $jokes = $this->searchJokes( $query );
    if (!$jokes) return false;

    $update->inline_query()->answer([
      'results' => array_map(function ($item){
        return [
          'id' => md5($item),
          'type' => 'article',
          'title' => strtr( $item, ['\n'=> ' '] ),
          'input_message_content' => [
            'message_text' => strtr( $item, ['\n' => "\n"] ),
          ],
        ];
      }, $jokes ),
      'cache_time' => 1,
      'is_personal' => true,
    ]);

    return false;
  }


  private function searchJokes($query )
  {

    // if no trigger defined, answer nothing
    if (!$trigger = $this->getOption('trigger')) return false;

    $filename =  $this->getOption('dir') . "/!$trigger.txt";

    $file = file($filename);
    if (empty( $query))
    {
      return false;
    }
    elseif (is_numeric( $query ) || $query[0] === '#' ){
      // number
      $rand   = preg_replace('@[^\d]+@', "", $query) * 1;
      if (!isset( $file[$rand-1])) return false;
      $found   = [
        $rand-1 => $file[$rand-1],
      ];
    }
    elseif ($query === 'last'){
      // number
      $count  = count($file);
      if (!isset( $file[$count-1])) return false;
      $found   = [
        $count-1 => $file[$count-1],
      ];
    }
    else {

      // exact match
      $found = array_filter( $file, function ($value) use ($query) {
        return preg_match('#\b'.preg_quote( $query ).'\b#iu', $value);
      });

      // relaxed match
      if (!count($found))
      {
        $found = array_filter( $file, function ($value) use ($query) {
          return preg_match('#'.preg_quote( $query ).'#iu', $value);
        });
      }

    }

    if (!count($found)) return false;

    foreach (array_slice( $found, 0, $this->getOption('limit', 10), true) as $key => $value)
    {
      $id = $key+1;
      $found[$key] = "!$trigger #$id: $value";
    }

    return array_values( $found );
  }

}