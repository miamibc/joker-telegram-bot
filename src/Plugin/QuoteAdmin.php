<?php
/**
 * Joker Quote Admin Plugin
 * Started in the sky, flight from Berlin to Tallinn 29 aug
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use RedBeanPHP\R;

class QuoteAdmin extends Base
{

  private $sessions = [];

  public function onPrivateText( Update $update )
  {
    // if not logged in
    if (!isset( $this->sessions[ $update->message()->from()->id() ]))
    {
      // only accept command login
      if ($update->message()->text()->trigger() !== 'login') return;
      // log in this user and reply with help message
      $this->sessions[ $update->message()->from()->id() ] = true;
      $message = <<<EOF
Hi, {$update->message()->from()->name()} you are logged in now.
You can work with triggers:

Commands:
<pre>cd [trigger]</pre> - to change trigger
<pre>ls</pre> - to list jokes
<pre>add</pre> - to add joke
<pre>rm</pre> - to remove joke
EOF;
      $update->answerMessage( $message , ['parse_mode' => 'HTML']);
    }



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



}