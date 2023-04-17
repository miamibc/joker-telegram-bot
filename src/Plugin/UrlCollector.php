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

use Joker\Parser\Update;

class UrlCollector extends Base
{

  protected $options = [
    'description' => 'URL collecting plugins',
    'risk' => 'MEDIUM. Save all URLs into the local file, stored locally not shared anywhere',
  ];

  public function onPublicTextEntities( Update $update )
  {
    $filename = $this->getOption('file', 'data/urls.txt');
    $message = $update->message();

    // search for urls in public message entities
    foreach ($message->entities() as $entity)
    {

      $url = '';

      // there are two types of urls in entity
      switch( $entity->type() )
      {
        case 'text_link':
          $url = $entity->url();
          break;
        case 'url':
          $url = $message->text()->substring( $entity->offset(), $entity->length() );
          break;
      }

      // append to the end of file, if url is found
      if ($url)
      {
        file_put_contents( $filename, $url . PHP_EOL, FILE_APPEND);
      }

    }
  }


}