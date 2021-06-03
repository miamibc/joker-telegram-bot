<?php
/**
 * UrlCollector plugin for Joker
 * Collects URLS from public messages
 *
 * Options:
 * - `file` (string, oprional, default 'data/urls.txt') - file to save urls to
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Plugin;
use Joker\Event;

class UrlCollector extends Plugin
{

  public function onPublicTextEntities( Event $event)
  {
    $filename = $this->getOption('file', 'data/urls.txt');
    $message = $event->message();

    // search for urls in public message entities
    foreach ($message->entities() as $entity)
    {
      // only url type is interesting for us
      if ($entity->type() !== 'url') continue;

      // extract from text
      $url = $message->text()->substring( $entity->offset(), $entity->length() );

      // append to the end of file
      file_put_contents( $filename, $url . PHP_EOL, FILE_APPEND);
    }
  }


}