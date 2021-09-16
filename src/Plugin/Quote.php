<?php
/**
 * Joker Quote Plugin
 * - Quotes from sqlite database
 * - Random quote
 * - Quote by number
 * - Search quote
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use RedBeanPHP\R;

class Quote extends Base
{

  private $triggers = [], $counter = 0;

  public function __construct($options = [])
  {
    parent::__construct($options);
    $this->triggers = self::jokeTriggers();
  }


  public function onPublicText( Update $update )
  {
    $text = $update->message()->text();
    $trigger = $text->trigger();

    // answer to !help and !list
    if (in_array( $trigger, [ 'help', 'list'] ))
    {
      $instructions = implode( PHP_EOL, [
        "Type <b>!topic</b> to find random quote",
        "Type <b>!topic search string</b> to search in topic and receive random joke",
        "Type <b>!topic number</b> to get specific quote by it's number",
        "Type <b>!topic last</b> to get last quote",
      ]);
      $triggers = implode(' ', array_map(function($item){
        return "!$item";
      }, $this->triggers));
      $update->answerMessage("$instructions\n\nList of topics: $triggers.", ['parse_mode' => 'HTML']);
      return false;
    }

    // now only triggers is acceptable
    if (!in_array( $trigger, $this->triggers)) return;

    // every 100 requests reload triggers
    if (++$this->counter % 100 === 0)
    {
      $this->triggers = self::jokeTriggers();
    }

    $query = $text->token(1);
    // no query
    if (empty( $query ))
    {
      $count  = R::count('joke', " trigger = ? ", [ $trigger ] );
      $joke   = R::findOne('joke', " trigger = ? ORDER BY random() LIMIT 1 ", [ $trigger ] );
      $prefix = "!$trigger $count total";
    }
    // numeric query
    elseif (is_numeric( $query ) || $query == 'last')
    {
      $count  = R::count('joke', " trigger = ? ", [ $trigger ] );
      $offset = is_numeric($query) ? $query-1 : $count-1;
      $joke   = R::findOne('joke', " trigger = ? ORDER BY id LIMIT $offset,1 ", [ $trigger ] );
      $prefix = "!$trigger $query of $count";
    }
    // string query
    else
    {
      $query  = '%' . strtr( mb_strtolower($query), [' ' => '%']) . '%';
      $count  = R::count('joke', " trigger = ? AND search LIKE ? ", [ $trigger, $query ] );
      $joke   = R::findOne('joke', " trigger = ? AND search LIKE ? ORDER BY random() LIMIT 1 ", [ $trigger, $query ] );
      $prefix = "!$trigger $count found";
    }

    $update->answerMessage( $joke ? "$prefix: $joke->joke" : "!$trigger: Sorry bro, nothing found :p");
    return false;

  }


  /**
   * List all active joke triggers
   * @return array
   */
  public static function jokeTriggers()
  {
    $result = [];
    foreach ( R::getAll('SELECT DISTINCT(trigger) FROM joke') as $item)
    {
      $result[] = $item['trigger'];
    }
    return $result;
  }

}