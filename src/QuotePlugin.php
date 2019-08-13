<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class QuotePlugin extends Plugin
{

  protected $options = [
    'dir' => false,
  ];

  public function onPublicText( Event $event )
  {
    $text = $event->getMessageText();

    $chunk = preg_split('@\s+@', $text);

    if ($chunk[0][0] !== '!') return;

    $command = trim( strtolower( preg_replace("@[^!\w]@", "", array_shift($chunk)) ));
    $params  = trim( implode(" ", $chunk) );

    $filename =  $this->getOption('dir') . "/$command.txt";
    if (!file_exists($filename)) return;

    $joke = $this->getJoke( $command, $params );
    $event->answerMessage( $joke );
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
            : "Oops. Noting found :(";

    return "$prefix: $joke";
  }
}