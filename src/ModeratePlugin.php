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
      'characters_between' => 255,
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
    if (!isset( $data['message']['from']['id'])) return;

    $id = $data['message']['from']['id'];

    // if no counter yet, create it with normal number of messages
    if (!isset( $this->counter[ $id ] ))
      $this->counter[ $id ] = $this->getOption('characters_between');

    $this->counter[ $id ] += strlen( @$data['message']['text'] );
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
      $data['message']['from']['id'],
      $data['message']['sticker']['file_id']
    )) return;

    $id = $data['message']['from']['id'];

    // if no counter yet, create it with normal number of messages
    if (!isset( $this->counter[ $id ] ))
      $this->counter[ $id ] = $this->getOption('characters_between');

    if ($this->counter[ $id ] < $this->getOption('characters_between'))
    {
      // sticker flood, delete it
      $event->deleteMessage();

      $need = $this->getOption('characters_between') - $this->counter[ $id ];

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
    $this->counter[ $id ] = 0;

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