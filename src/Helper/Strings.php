<?php

namespace Joker\Helper;

use Carbon\Carbon;

class Strings
{

  /**
   * @param \DateTime|string $datetime
   * @param false $full short or long version
   *
   * @return string
   */
  public static function timeElapsed( $datetime, $full = false)
  {
    $now = new \DateTime;
    $ago = is_string($datetime) ? new \DateTime($datetime) : $datetime;
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
      'y' => 'year',
      'm' => 'month',
      'w' => 'week',
      'd' => 'day',
      'h' => 'hour',
      'i' => 'minute',
      's' => 'second',
    );
    foreach ($string as $k => &$v) {
      if ($diff->$k) {
        $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
      } else {
        unset($string[$k]);
      }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
  }

  /**
   * Returns time difference in words
   * @param $from
   * @param $to
   *
   * @return string
   */
  public static function diffTimeInWords($from,$to)
  {

    $date1 = new \DateTime( is_numeric($from) ? "@$from" : "$from");
    $date2 = new \DateTime( is_numeric($to)   ? "@$to"   : "$to");
    $interval = $date1->diff($date2);
    $result = [];
    foreach (['%y'=>'years', '%m' => 'months', '%d' => 'days', '%h' => 'hours', '%i' => 'minutes', '%s' => 'seconds'] as $key => $value)
    {
      if ($num = $interval->format($key))
        $result[] = "$num $value";
    }
    return implode(" ", $result);
  }

  /**
   * Convert text from one character set to another (transliteration or back from translit to cyrillic).
   * @param string $text text to translate
   * @param string $from source character set
   * @param string $to   destination character set
   * @return array|string|string[]
   */
  public static function transliterate(string $text, string $from = 'cyr', string $to = 'lat' )
  {
    $tables = [
      'cyr' => ['Љ', 'Њ', 'Џ', 'џ', 'ш', 'ђ', 'ч', 'ћ', 'ж', 'љ', 'њ', 'Ш', 'Ђ', 'Ч', 'Ћ', 'Ж','Ц','ц', 'а','б','в','г','д','е','ё','ж','з','и','й','к','л','м','н','о','п', 'р','с','т','у','ф','х','ц','ч','ш','щ','ъ','ы','ь','э','ю','я', 'А','Б','В','Г','Д','Е','Ё','Ж','З','И','Й','К','Л','М','Н','О','П', 'Р','С','Т','У','Ф','Х','Ц','Ч','Ш','Щ','Ъ','Ы','Ь','Э','Ю','Я'],
      'lat' => ['Lj', 'Nj', 'Dzh', 'dzh', 'sh', 'đ', 'ch', 'ch', 'zh', 'lj', 'nj', 'Sh', 'Đ', 'Ch', 'C', 'Zh','C','c', 'a','b','v','g','d','e','yo','zh','z','i','j','k','l','m','n','o','p', 'r','s','t','u','f','h','ts','ch','sh','sht','a','i','y','e','yu','ya', 'A','B','V','G','D','E','Io','Zh','Z','I','Y','K','L','M','N','O','P', 'R','S','T','U','F','H','Ts','Ch','Sh','Sht','A','I','Y','e','Yu','Ya'],
    ];
    return str_replace($tables[$from], $tables[$to], $text);
  }

}