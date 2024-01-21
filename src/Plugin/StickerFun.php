<?php
/**
 * Joker StickerFun Plugin
 * Send random sticker from previously posted, when people started to send lots of stickers
 *
 * Options:
 * - `range` integer, optional, default 300 - defines a time frame (seconds) to search stickers activity in
 * - `delay` integer, optional, default 3 - random delay before sending answer sticker, up to .x. seconds
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class StickerFun extends Base
{

  protected $options = [
    'delay' => 3,
    'range'  => 300,         // timeframe to check stickers

    'description' => 'Bot answers with stickers on public chat',
    'risk' => 'LOW. Nothing stored or shared',
  ];

  protected $sets_used = []; // remember all used sticker sets
  protected $timeline  = []; // record sticker activity

  public function onPublicSticker( Update $update )
  {

    $now = time();

    // remember all used sticker sets
    $set_name = $update->message()->sticker()->set_name();
    if (!isset($this->sets_used[$set_name])) $this->sets_used[$set_name] = 0;
    $this->sets_used[ $set_name ]++;

    // record stickers timeline
    $this->timeline[] = [ $now, $update->message()->from()->id() ];

    // filter off old records
    $time = $now-$this->getOption('range');
    $users = [];
    $this->timeline = array_filter( $this->timeline, function ($item) use (&$users, $time){
      if ($item[0] > $time) { $users[] = $item[1]; return true; }
    });

    // no stickers in range, nothing to do
    if (!count($this->timeline)) return;

    // simple math with number of stickers and number of users in range
    $chance = ceil($this->getOption('range')/10/(count($this->timeline)*2)/(count(array_unique($users))*5));

    // send sticker if random is exactly 0
    if (mt_rand(0, $chance)) return;

    // request stickers from random pack
    $sets   = array_keys($this->sets_used);
    $set    = $sets[mt_rand(0, count($sets)-1)];
    $result = $update->customRequest('getStickerSet', ['name' => $set]);

    // error or no stickers in set? remove from list of sets
    if (!isset($result['stickers']) || !count($result['stickers']))
    {
      unset ($this->sets_used[ $set ]);
      return;
    }

    // choose random sticker
    $file_id = $result['stickers'][ mt_rand(0, count($result['stickers'])-1) ]['file_id'];

    // send action
    $update->message()->chat()->sendAction(Update::ACTION_STICKER);

    // random delay
    if ($delay = mt_rand(0, $this->getOption('delay')))
      sleep( $delay );

    // send it
    $update->answerSticker( $file_id );

    // clean timeline
    $this->timeline = [];
    return false;

  }
}