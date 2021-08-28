<?php
/**
 * Joker Quote Plugin
 * - Quotes from file in specified directory
 * - Random quote
 * - Quote by number
 * - Seach quote
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
    $this->loadTriggers();
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
      ]);
      $triggers = implode(' ', array_map(function($item){
        return "!$item";
      }, $this->triggers));
      $update->answerMessage("$instructions\n\nList of topics: $triggers.", ['parse_mode' => 'HTML']);
      return false;
    }

    if (!in_array( $trigger, $this->triggers)) return;

    // no query
    if (empty($query = $text->token(1)))
    {
      $count = R::count('joke', " trigger = ? ", [ $trigger ] );
      $prefix = "!$trigger $count total";
      $joke = R::findOne('joke', " trigger = ? ORDER BY random() LIMIT 1 ", [ $trigger ] );
    }
    // numeric query
    elseif (is_numeric( $query))
    {
      $offset = $query-1;
      $count = R::count('joke', " trigger = ? ", [ $trigger ] );
      $prefix = "!$trigger $query of $count";
      $joke = R::findOne('joke', " trigger = ? ORDER BY id LIMIT $offset,1 ", [ $trigger ] );
    }
    // string query
    else
    {
      $query = '%' . strtr( mb_strtolower($query), [' ' => '%']) . '%';
      $count = R::count('joke', " trigger = ? AND search LIKE ? ", [ $trigger, $query ] );
      $prefix = "!$trigger $count found";
      $joke = R::findOne('joke', " trigger = ? AND search LIKE ? ORDER BY random() LIMIT 1 ", [ $trigger, $query ] );
    }

    $update->answerMessage( $joke ? "$prefix: $joke->joke" : "!$trigger: Sorry bro, nothing found :p");
    return false;

  }

  /**
   * Listen to private message and add joke
   *
   * @param Update $update
   */
  public function onPrivateTextDisabled( Update $update )
  {
    $text = $update->message()->text();

    if ( preg_match( $regexp = '#^(.*), \[([^]]+)\]\n(.*?)$#m', $text, $matches) )
    {
      // multi-line telegram-x format
      $text = preg_replace($regexp,'<\1> \3',$text); // make <name> text lines
      $text = preg_replace('#\n+#m','\n',$text);     // change newlines to special newline
      $text = '['.$matches[2].']\n'.trim($text);     // add date
    }
    elseif (preg_match_all('#^(.*):#m', $text, $matches, PREG_OFFSET_CAPTURE))
    {
      // multi-line telegram format
      $result = [];
      foreach ($matches[1] as $num => $match)
      {
        $start = strlen($match[0])+2+$match[1];  // calculate start of message by adding length of name to start offset
        $message = isset($matches[1][$num+1][1]) // if next match exists
                  ? substr( $text, $start, $matches[1][$num+1][1] - $start) // get text from start to next offset
                  : substr( $text, $start) // otherwise get all
                  ;
        $message = preg_replace('#\n+#m','\n',trim( $message )); // replace newlines with special newline
        $result[] = "<$match[0]> $message";
      }
      $text = implode('\n', $result);
    }
    else
    {
      // old-school, IRC-joker format
      $text = trim($text);
      $text = preg_replace('#\n+#m','\n',$text); // replace newlines with special newlines
      $text = preg_replace('#\s+#m',' ',$text);  // replace long spaces to normal spaces
    }

    file_put_contents( $this->getOption('dir') . '/!tg.txt', PHP_EOL.$text, FILE_APPEND);

    // return last joke
    $joke = $this->getJoke( "!tg", "last" );
    $update->answerMessage( "Added: $joke" );
    return false;
  }

  private function loadTriggers()
  {
    $this->triggers = [];
    // list all active triggers
    foreach ( R::getAll('SELECT DISTINCT(trigger) FROM joke') as $item)
    {
      $this->triggers[] = $item['trigger'];
    }
  }


}