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

use Joker\Plugin;
use Joker\Event;

class Quote extends Plugin
{

  protected $options = [
    'dir' => false,
  ];

  public function onPublicText( Event $event )
  {
    $text = $event->getMessageText();

    $chunk = preg_split('@\s+@', $text);

    if ($chunk[0][0] !== '!') return;

    $command = trim( strtolower( preg_replace("@[^!\w\d]@", "", array_shift($chunk)) ));
    $params  = trim( implode(" ", $chunk) );

    if (in_array( $command, [ '!list', '!help' ]) )
    {
      $help = $this->getHelp( $this->getOption("dir") );
      $event->answerMessage( $help );
      return false;
    }

    $filename =  $this->getOption('dir') . "/$command.txt";
    if (!file_exists($filename)) return;

    $joke = $this->getJoke( $command, $params );
    $event->answerMessage( $joke );
    return false;
  }

  /**
   * Listen to private message and add joke
   * @param Event $event
   */
  public function onPrivateText( Event $event )
  {
    $text = $event->getMessageText();

    $regexp = '#^(.*), \[([^]]+)\]\n(.*?)$#m';

    if ( preg_match(  $regexp, $text, $matches) )
    {
      // multi-line telegram-joker format
      $text = preg_replace($regexp,'<\1> \3',$text);
      $text = preg_replace('#\n+#m','\n',$text);
      $text = '['.$matches[2].']\n'.trim($text);
    }
    else
    {
      // old-school, IRC-joker format
      $text = preg_replace('#\s+#m',' ',$text);
      $text = trim($text);
    }

    file_put_contents( $this->getOption('dir') . '/!tg.txt', PHP_EOL.$text, FILE_APPEND);

    // return last joke
    $joke = $this->getJoke( "!tg", "last" );
    $event->answerMessage( "Added: $joke" );
    return false;
  }


  private  function getHelp( $dir )
  {
    $topics = [];
    foreach ( glob( "$dir/*.txt" ) as $filename)
    {
      if (pathinfo($filename, PATHINFO_EXTENSION) !== 'txt') continue;
      $topics[] = basename( $filename, '.txt');
    }
    return "List of " . basename($dir) . ": " . implode(" ", $topics);
  }

  private function getJoke( $command, $params )
  {

    $filename =  $this->getOption('dir') . "/$command.txt";

    $file = file($filename);
    if (empty( $params))
    {
      // random
      $count  = count($file);
      $rand   = mt_rand(1, $count);
      $prefix = "$command $rand of $count";
    }
    elseif ( is_numeric( $params ) || $params[0] === '#' ){
      // number
      $rand   = preg_replace('@[^\d]+@', "", $params)*1;
      $count  = count($file);
      $prefix = "$command $rand of $count";
    }
    elseif ( $params === 'last'){
      // number
      $count  = count($file);
      $rand   = $count;
      $prefix = "$command $rand of $count";
    }
    else {

      // exact match
      $found = array_filter( $file, function ($value) use ($params) {
        return preg_match('#\b'.preg_quote( $params ).'\b#iu', $value);
      });

      // relaxed match
      if (!count($found))
      {
        $found = array_filter( $file, function ($value) use ($params) {
          return preg_match('#'.preg_quote( $params ).'#iu', $value);
        });
      }

      $count  = count( $found );
      $rand   = $count ? array_rand($found) + 1 : 0;
      $prefix = $count ? "$command $rand of $count" : "$command";
    }

    $joke = $count && isset( $file[$rand-1] )
            ? strtr($file[$rand-1], ['\n'=>"\n"])
            : "Joke not found :(";

    return "$prefix: $joke";
  }
}