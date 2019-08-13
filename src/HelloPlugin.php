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

    if (!preg_match('@^(hi|hello|moin|yo|wa\wa\w*)\b@ui', $event->getMessageText())) return;

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

    // by default, answer with same sticker
    $file_id = $data['message']['sticker']['file_id'];

    // if possible, load others stickers
    if (isset($data['message']['sticker']['set_name']))
    {
      $stickers = [];
      $result = $event->customRequest('getStickerSet', ['name'=>$data['message']['sticker']['set_name']]);
      foreach ($result['stickers'] as $sticker)
      {
        $stickers[] = $sticker['file_id'];
      }
      shuffle($stickers);
      $file_id = $stickers[ mt_rand(0, count($stickers)-1) ];
    }
    $event->answerSticker( $file_id );
  }
}