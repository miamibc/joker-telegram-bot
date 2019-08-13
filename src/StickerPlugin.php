<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker;

class StickerPlugin extends Plugin
{

  protected
    $options = [
    'time_between' => 60,
    ];

  private
    $last_good_sticker_time = 0,
    $stickers = [];

  public function scanLog( $filename )
  {
    foreach (file($filename) as $line)
    {
      $data = json_decode($line, true);
      if (!isset($data['message']['date'], $data['message']['sticker']['file_id'])) continue;

      $this->last_good_sticker_time = $data['message']['date'];
      $this->stickers[] = $data['message']['sticker']['file_id'];
    }

    $this->stickers = array_unique( $this->stickers );
    shuffle($this->stickers);
  }


  public function onPublicSticker( Event $event)
  {
    $data = $event->getData();

    if (!isset( $data['message']['date'], $data['message']['sticker']['file_id'])) return;

    $this->stickers[] = $data['message']['sticker']['file_id'];
    $this->stickers = array_unique($this->stickers);
    shuffle($this->stickers);

    if (!$this->last_good_sticker_time ||
        $data['message']['date'] > $this->last_good_sticker_time+$this->options['time_between'] )
    {
      $this->last_good_sticker_time = $data['message']['date'];
    }
    else
    {
      $event->deleteMessage();
    }
  }
}