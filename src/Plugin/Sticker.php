<?php
/**
 * Joker Sticker Plugin
 * Send sticker to Joker, he answers with random sticker from same pack.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Sticker extends Base
{

  public function onPrivateSticker( Update $update )
  {

    $sticker = $update->message()->sticker();

    // request stickers in this pack
    $result = $update->customRequest('getStickerSet', ['name' => $sticker->set_name()]);

    // error or no stickers in set?
    if (!isset($result['stickers'])) return;

    // collect stickers from pack but not same sticker
    $ids = [];
    foreach ($result['stickers'] as $item)
      if ($item['file_id'] !== $sticker->file_id())
        $ids[] = $item['file_id'];

    // random sticker from collected, or same if nothing there
    $answer = count($ids) ? $ids[ mt_rand(0, count($ids)-1) ] : $sticker->file_id();
    $update->answerSticker( $answer );
    return false;

  }
}