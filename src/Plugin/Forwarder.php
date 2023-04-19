<?php
/**
 * Forwarder plugin fro Joker
 *
 * Forwards messages from one chat to another
 *
 * Array or options consists of items with elements:
 * - from - (number or array of numbers) one or many chat_ids to read messages from
 * - text - (string or array of strings) one or many patterns of text with *wildcards* or ?questions?
 * - to   - (number or array of numbers) one or many chat_ids to send message to
 * - forward - (bool, default is true)  should bot forward message, or just copy
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Forwarder extends Base
{

  protected $options = [
    'description' => 'Forwards texts to another channel',
    'risk' => 'MEDIUM. Messages from user can be forwarded to another channel',
  ];

  public function onMessageText( Update $update )
  {

    $text = $update->message()->text();

    foreach ($this->getOptions() as $item)
    {

      // normalize all options inside item
      $item = $this->normalizeItem($item);

      // skip if not from needed chat
      if (!in_array( $update->message()->chat()->id(), $item['from'])) continue;

      // check all patterns, skip if nothing found
      $found = false;
      foreach ( $item['text'] as $pattern)
        if (fnmatch($pattern, $text)) $found = true;
      if (!$found) continue;

      // message is okay for sending

      foreach ($item['to'] as $chat_id)
      {

        // copy message, or forward it
        if ($item['forward'])
          $update->forwardMessage( $chat_id );
        else
          $update->sendMessage($chat_id,$text);
      }

    }

  }

  private function normalizeItem( $item )
  {

    // normalize from, make array
    if (!isset($item['from'])) $item['from'] = [];
    if (!is_array($item['from'])) $item['from'] = [$item['from']];

    // normalize to, make array
    if (!isset($item['to'])) $item['to'] = [];
    if (!is_array($item['to'])) $item['to'] = [$item['to']];

    // normalize forward, default is true
    if (!isset($item['forward'])) $item['forward'] = true;

    // normalize text
    if (!isset($item['text'])) $item['text'] = [];
    if (!is_array($item['text'])) $item['text'] = [$item['text']];

    return $item;
  }

}