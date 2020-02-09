<?php
/**
 * Random joke from Bash.im, plugin for Joker
 *
 * Ask random track or search:
 *   !bash
 *
 * Bot will answer with random joke
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class BashPlugin extends Plugin
{

  const ENDPOINT  = 'https://bash.im/forweb/?u';

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/bash|!bash)\b(.*)?$@ui', $text, $matches)) return;

    $trigger = trim( $matches[1] );
    $query   = trim( $matches[2] );

    $content = file_get_contents(self::ENDPOINT);

    if (!preg_match('@color: #21201e">(.*?)<\' \+ \'/div>@im',$content, $matches))
    {
      $event->answerMessage( "Sorry, looks like server down, try again later..." );
      return false;
    }

    $content = html_entity_decode( strtr($matches[1], ["<' + 'br>"=>"\n"]));
    $event->answerMessage( "$trigger: $content" );
    return false;

  }

}