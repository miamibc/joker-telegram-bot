<?php
/**
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'autoload.php';

$text  = file_get_contents($argv[1]);

if ( preg_match( $regexp = '#^(.*), \[([^]]+)\]\n(.*?)$#m', $text, $matches) )
{
  // multi-line telegram-x format
  $text = preg_replace($regexp,'<\1> \3',$text); // make <name> text lines
  //$text = preg_replace('#\n+#m','\n',$text);     // change newlines to special newline
  $text = '['.$matches[2].']\n'.trim($text);     // add date
}

echo $text;
