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
use Wamania\Snowball\StemmerManager;

class Stats extends Base
{

  protected $options = [
    'description' => 'Frequency analysis of words said by chat members',
    'risk' => 'MEDIUM. Accidentially, in stats result some secret information can appear, for example if you repeat your password lots of times :D Be careful what you write on a public channel',
  ];

  public function onPublicText( Update  $update)
  {
    if ($update->message()->text()->trigger() !== 'stats') return;

    $update->answerMessage( implode(PHP_EOL, $this->personStats($update->message()->from())));
    return false;
  }

  public function personStats( User $user )
  {

    $file = fopen( $this->getOption( 'file' ), 'r');

    $lines = 0; $processed = 0; $words = []; $forms = [];
    $stemmer = new StemmerManager();
    $start = strtotime('1 month ago');

    while ( ($data = fgets( $file )) !== false)
    {
      $lines++;
      $update = new Update( json_decode( $data, true ) );
      $tags = $update->getTags();

      // skip non-public messages
      if (!$tags['Message'] || !$tags['Public']) continue;

      // skip old dates
      if ($update->message()->date() < $start) continue;

      // just in case, skip messages without user
      if (!$update->message()->from()) continue;

      // skip wrong users
      if ($update->message()->from()->id() !== $user->id()) continue;

      // process texts, english and russian separately because stemmer needs to know the language
      foreach ([$update->message()->text(), $update->message()->caption()] as $text)
      {
        if (!$text) continue;
        foreach (preg_match_all('@\b[a-z]{6,}\b@iu', "$text", $matches) ? $matches[0] : [] as $word )
        {
          $word = mb_strtolower($word) ;
          $firstform = $stemmer->stem( $word , 'en');
          if (!isset($words[$firstform])) $words[$firstform] = 0;
          $words[$firstform]++;
          $forms[$firstform][$word] = true;
        }
        foreach (preg_match_all('@\b[а-яё]{6,}\b@iu', "$text", $matches) ? $matches[0] : [] as $word )
        {
          $word = mb_strtolower($word) ;
          $firstform = $stemmer->stem( $word , 'ru');
          if (!isset($words[$firstform])) $words[$firstform] = 0;
          $words[$firstform]++;
          $forms[$firstform][$word] = true;
        }
      }

      $processed++;
    }
    fclose( $file );

    arsort($words, SORT_NUMERIC);

    $result = ["$lines total lines in log, processed $processed public messages from $user for past month, minimum word length 6 symbols. Top words:"];

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