<?php
/**
 * Stats Plugin for Joker
 *
 * Ask joker fro your stats:
 *   !stats
 *
 * After few seconds of thinking, bot will answer you with your top words:
 *
 *   406681 total lines in log, processed 495 public messages from Eduard Z during past month, minimum word length 6 symbols. Top words:
 *   - 16 тольк (только)
 *   - 9 больш (больше, большой, больши)
 *   - 8 youtube (youtube)
 *   - 7 сейчас (сейчас)
 *   - 6 спасиб (спасибо)
 *   - 6 прост (просто, простите)
 *   - 6 вообщ (вообще)
 *   - 5 канал (каналов, каналы, канале)
 *   - 5 сегодн (сегодня)
 *   - 5 деньг (деньги, деньгах)
 *
 * Configuration options:
 * - `file` (string, required) Path to log file (ame as in [Log Plugin](#log-plugin))
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use Joker\Parser\User;

class Stats extends Base
{

  public function onPublicText( Update  $update)
  {
    if ($update->message()->text()->trigger() !== 'stats') return;

    $update->answerMessage( implode(PHP_EOL, $this->personStats($update->message()->from())));
    return false;

  }

  public function personStats( User $user)
  {

    $file = fopen( $this->getOption( 'file' ), 'r');

    $i = 0; $processed = 0; $words = []; $forms = [];
    $stemmer = new \NXP\Stemmer();
    $start = strtotime('1 month ago');

    while ( ($data = fgets( $file )) !== false)
    {
      $i++;
      $line = json_decode( $data, true );
      $update = new Update( $line );
      $tags = $update->getTags();
      if (!$tags['Public']) continue;
      if (empty($update->message())) continue;
      if (empty($update->message()->from())) continue;

      // skip wrong users
      if ($update->message()->from()->id() !== $user->id()) continue;

      // skip old dates
      if ($update->message()->date() < $start) continue;

      if ($text = $update->message()->text())
      {
        $processed++;
        foreach (preg_match_all('@\b[a-zа-яё]{6,}\b@iu', $text, $matches) ? $matches[0] : [] as $word )
        {
          $word = mb_strtolower($word) ;
          $firstform = $stemmer->getWordBase( $word );
          if (!isset($words[$firstform])) $words[$firstform] = 0;
          $words[$firstform]++;
          $forms[$firstform][$word] = true;
        }
      }
    }
    fclose( $file );

    arsort($words, SORT_NUMERIC);

    $result = ["$i total lines in log, processed $processed public messages from $user for past month, minimum word length 6 symbols. Top words:"];

    if (empty($words))
    {
      $result[] = "S0rry d0g, looks like you said nothing interesting :p";
      return $result;
    }

    foreach ( array_slice($words, 0, 10, true) as $word => $count )
        $result[] = "- $count $word (" . implode(', ', array_keys( $forms[$word] )) . ')';

    return $result;
  }
}