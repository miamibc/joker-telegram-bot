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

use Joker\Parser\Update;

class Meme extends Base
{

  protected $options = [
    'description' => 'Memes generator',
    'risk' => 'MEDIUM. Anonymous request\'s text can be visible in api.memegen.link access logs by site administrators',
  ];

  public function onPublicText( Update $update )
  {
    // process only if triggered by 'meme' trigger
    if ($update->message()->text()->trigger() !== 'meme') return;

    // selected meme
    $name = $update->message()->text()->token(1,1);

    // if nothing selected, or it's 'list'
    if ( empty($name) || $name == 'list')
    {
      // make list of memes
      $list = array_map(function ($item){
        $template = $item['template'];
        return substr( $template, strlen( 'https://api.memegen.link/templates/' ));
      }, json_decode( file_get_contents('https://api.memegen.link/images/'), true));

      // answer instrctions
      $update->answerMessage("Usage: !meme <name>\nthen add one, or two lines of text.\nName can be selected from: ".implode(" ", $list));
      return false;
    }

    // download template
    $template = @file_get_contents( "https://api.memegen.link/templates/$name" );

    // if no template received, no such meme
    if (!$template)
    {
      $update->answerMessage("S0rry d0g, no such meme");
      return false;
    }

    $template = json_decode( $template, true );
    $lines = $template['lines'];

    // process amount of lines from 1, replace special symbols and make a link
    $text = trim( strtr( $update->message()->text()->line(1, $lines), [
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
      $update->answerMessage("Usage: !meme $name\n$lines lines of text");
      return false;
    }

    // download image
    $image = @file_get_contents( "https://api.memegen.link/images/$name/$text.png" );

    // if image downloaded, save to temp directory and answer
    if ($image)
    {
      file_put_contents( 'data/tmp/meme.png', $image);
      $update->answerPhoto( 'data/tmp/meme.png' );
      unlink('data/tmp/meme.png' );
    }
    else
    {
      $update->answerMessage("S0rry d0g, something went wrong");
    }

    return false;

  }

}