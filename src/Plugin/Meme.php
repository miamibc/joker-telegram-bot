<?php
/**
 * Meme plugin for Joker Telegram Bot
 *
 * Generates meme from http://memegen.link API
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Event;
use Joker\Plugin;

class Meme extends Plugin
{

  public function onPublicText( Event $event )
  {
    // process only if triggered by 'meme' trigger
    if ($event->message()->text()->trigger() !== 'meme') return;

    // selected meme
    $name = $event->message()->text()->token(1,1);

    // if nothing selected, or it's 'list'
    if ( empty($name) || $name == 'list')
    {
      // make list of memes
      $list = array_map(function ($item){
        $template = $item['template'];
        return substr( $template, strlen( 'https://api.memegen.link/templates/' ));
      }, json_decode( file_get_contents('https://api.memegen.link/images/'), true));

      // answer instrctions
      $event->answerMessage("Usage: !meme <name>\nthen add one, or two lines of text.\nName can be selected from: " . implode(" ", $list));
      return false;
    }

    // download template
    $template = @file_get_contents( "https://api.memegen.link/templates/$name" );

    // if no template received, no such meme
    if (!$template)
    {
      $event->answerMessage("S0rry d0g, no such meme");
      return false;
    }

    $template = json_decode( $template, true );
    $lines = $template['lines'];

    // process amount of lines from 1, replace special symbols and make a link
    $text = trim( strtr( $event->message()->text()->line(1, $lines), [
        "\n" => '/',
        ' ' => '_',
        '_' => '__',
        '-' => '--',
        '?' => '~q',
        '&' => '~a',
        '%' => '~p',
        '#' => '~h',
        '/' => '~s',
        '\\' => '~b',
        '"' => "''",
      ]));

    if (empty($text))
    {
      $event->answerMessage("Usage: !meme $name\n$lines lines of text");
      return false;
    }

    // download image
    $image = @file_get_contents( "https://api.memegen.link/images/$name/$text.png" );

    // if image downloaded, save to temp directory and answer
    if ($image)
    {
      file_put_contents( 'data/tmp/meme.png', $image);
      $event->answerPhoto( 'data/tmp/meme.png' );
      unlink('data/tmp/meme.png' );
    }
    else
    {
      $event->answerMessage("S0rry d0g, something went wrong");
    }

    return false;

  }

}