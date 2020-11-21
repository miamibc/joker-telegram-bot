<?php
/**
 * Joker Sticker Plugin
 * Send sticker to Joker, he answers with random sticker from same pack.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class Sticker extends Plugin
{

  public function onPrivateSticker( Event $event )
  {
    $data = $event->getData();

    // check requirements
    if (!isset(
      $data['message']['sticker']['file_id'],
      $data['message']['sticker']['set_name']
    )) return;

    $file_id = $data['message']['sticker']['file_id'];

    // request stickers in this pack
    $result = $event->customRequest('getStickerSet', ['name'=>$data['message']['sticker']['set_name']]);

    // error or no stickers in set?
    if (!isset($result['stickers'])) return;

    // collect stickers from pack but not same sticker
    $stickers = [];
    foreach ($result['stickers'] as $sticker)
      if ($stickers['file_id'] !== $file_id)
        $stickers[] = $sticker['file_id'];

    // random sticker from collected, or same if nothing there
    $answer = count($stickers) ? $stickers[ mt_rand(0, count($stickers)-1) ] : $file_id;
    $event->answerSticker( $answer );
    return false;

  }
}