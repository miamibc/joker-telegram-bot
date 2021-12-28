<?php
/**
 * QuoteInline plugin for Joker
 *
 * Type bot @username with text to search in jokes
 * Then select any joke from list, this will post a joke via the bot
 *
 * Configuration options:
 * - `dir`     (string, optional, default data/jokes) directory with jokes
 * - `limit`   (integer, optional, default 10) maximum number of jokes to display in suggestion block
 * - `trigger` (string, required) for now this plugin allows to serve only one file with jokes, type it's name here
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use RedBeanPHP\R;

class QuoteInline extends Base
{

  protected $options = [
    'trigger' => 'tg',
    'limit' => 10,
  ];

  public function onInline( Update $update )
  {
    if (!$trigger = $this->getOption('trigger')) return;
    $limit = $this->getOption('limit');
    $query  = '%' . strtr( mb_strtolower($update->inline_query()->query()), [' ' => '%']) . '%';
    $jokes   = R::find('joke', " trigger = ? AND search LIKE ? ORDER BY random() LIMIT ? ", [ $trigger, $query, $limit ] );
    if (!$jokes) return false;

    $update->inline_query()->answer([
      'results' => array_map(function ($item){
        return [
          'id' => md5($item),
          'type' => 'article',
          'title' => strtr( $item, ['\n'=> ' '] ),
          'input_message_content' => [
            'message_text' => strtr( $item, ['\n' => "\n"] ),
          ],
        ];
      }, $jokes ),
      'cache_time' => 1,
      'is_personal' => true,
    ]);
    return false;
  }


}