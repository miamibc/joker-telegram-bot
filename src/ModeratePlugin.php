<?php
/**
 * Joker Moderator Plugin
 *   Moderates channel or group - removes stickers flood
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class ModeratePlugin extends Plugin
{

  protected
    $options = [
      'characters_between' => 555,
    ];

  private $counter = [];

  /**
   * Listen to public text mesage and increase counter
   * @param Event $event
   */
  public function onPublicTextMessage( Event $event )
  {
    $data = $event->getData();

    // requirements
    if (!isset( $data['message']['chat']['id'])) return;

    $chat_id = $data['message']['chat']['id'];

    // if no counter yet, create it with normal number of messages
    if (!isset( $this->counter[$chat_id]))
      $this->counter[ $chat_id ] = $this->getOption('characters_between');

    $this->counter[ $chat_id ] += strlen( @$data['message']['text'] );
  }

  /**
   * Listen to public sticker and delete it, if counter less than allowed
   *
   * @param Event $event
   *
   * @return int|void
   */
  public function onPublicStickerMessage( Event $event)
  {
    $data = $event->getData();

    // check requirements
    if (!isset(
      $data['message']['date'],
      $data['message']['chat']['id'],
      $data['message']['sticker']['file_id']
    )) return;

    $chat_id = $data['message']['chat']['id'];

    // if no counter yet, create it with normal number of messages
    if (!isset( $this->counter[$chat_id]))
      $this->counter[ $chat_id ] = $this->getOption('characters_between');

    if ($this->counter[ $chat_id ] < $this->getOption('characters_between'))
    {
      // sticker flood, modelete it
      $event->deleteMessage();

      $need = $this->getOption('characters_between') - $this->counter[ $chat_id ];

      // say something
      $name = $event->getMessageFrom();
      $answer = [
        "Can't post this sh#t righ now, $name. Need $need more chars to post sticker.",
        "$name, you're little damn flooder. Need $need more chars to post sticker.",
        "No sh1t, $name m4n. Need $need more chars to post sticker.",
      ];
      $event->answerMessage( $answer[ array_rand($answer) ]);
      return;
    }

    // ok, reset counter
    $this->counter[ $chat_id ] = 0;

    // and (just for fun) answer with random sticker from same set
    if (isset($data['message']['sticker']['set_name']))
    {
      $stickers = [];
      $result = $event->customRequest('getStickerSet', ['name'=>$data['message']['sticker']['set_name']]);
      foreach ($result['stickers'] as $sticker)
      {
        $stickers[] = $sticker['file_id'];
      }
      $file_id = $stickers[ mt_rand(0, count($stickers)-1) ];
      $event->answerSticker( $file_id );
      return Bot::PLUGIN_BREAK;
    }

  }
}