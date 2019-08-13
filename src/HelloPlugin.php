<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class HelloPlugin extends Plugin
{

  public function onPrivateText( Event $event )
  {

    // if (!preg_match('@$(hi|hello|moin)\b@i', $update->getMessageText())) return;

    $name = $event->getMessageFrom();

    $greetings = [
      "Hello, $name. I'm Joker the bot",
      "Hi, $name what's up",
      "Nice to meet you, $name. How r u?",
    ];

    $rand = array_rand( $greetings );

    $event->answerMessage( $greetings[ array_rand($greetings) ]);
  }


  public function onPrivateSticker( Event $event )
  {
    $data = $event->getData();
    if (!isset($data['message']['sticker']['file_id'])) return;
    $event->answerSticker( $data['message']['sticker']['file_id'] );
  }
}