<?php
/**
 * Cowsay plugin for Joker
 *
 * Classic console fun now is in Joker. Say
 *   !cowsay Moo
 *
 * bot will answer:
 *   < Moo >
 *        \   ^__^
 *         \  (oo)\_______
 *            (__)\       )\/\
 *               ||----w |
 *              ||     ||
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

use Cowsayphp\Farm;

class CowsayPlugin extends Plugin
{

  private $cow;

  public function __construct($options = [])
  {
    parent::__construct($options);
    $this->cow = Farm::create(Farm\Cow::class);
  }

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (!preg_match('@^(/cowsay|!cowsay)\b@ui', $text)) return;

    if (!preg_match('@^(/cowsay|!cowsay)\s(.+)$@ui', $text, $matches))
    {
      $event->answerMessage("Usage: !cowsay text");
      return false;
    }

    if (!$message = trim($matches[2]))
    {
      $event->answerMessage("Usage: !cowsay text");
      return false;
    }

    $answer = trim( htmlentities( $this->cow->say( $message ) ) );
    $event->answerMessage( "<pre>$answer</pre>", ['parse_mode'=>'HTML'] );
    return false;
  }

}