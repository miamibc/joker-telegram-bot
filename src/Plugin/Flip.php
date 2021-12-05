<?php
/**
 * Flip plugin for Joker
 *
 * Flips text upside-down and back
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Flip extends Base
{

  private static $table = [
    "a"      => "\u{0250}",
    "b"      => "q",
    "c"      => "\u{0254}",
    "d"      => "p",
    "e"      => "\u{01DD}",
    "f"      => "\u{025F}",
    "g"      => "\u{0183}",
    "h"      => "\u{0265}",
    "i"      => "\u{0131}",
    "j"      => "\u{027E}",
    "k"      => "\u{029E}",
    //l : '\u0283",
    "m"      => "\u{026F}",
    "M"      => "W",
    "n"      => "u",
    "r"      => "\u{0279}",
    "t"      => "\u{0287}",
    "v"      => "\u{028C}",
    "w"      => "\u{028D}",
    "y"      => "\u{028E}",
    "."      => "\u{02D9}",
    "["      => "]",
    "("      => ")",
    "{"      => "}",
    "?"      => "\u{00BF}",
    "!"      => "\u{00A1}",
    "'"      => ",",
    "<"      => ">",
    "_"      => "\u{203E}",
    "\u203F" => "\u{2040}",
    "\u2045" => "\u{2046}",
    "\u2234" => "\u{2235}",
    "\r"     => "\n",
    "а"      => "ɐ",
    "б"      => "ƍ",
    "в"      => "ʚ",
    "г"      => "ɹ",
    "д"      => "ɓ",
    "ё"      => "ǝ",
    "е"      => "ǝ",
    "ж"      => "ж",
    "з"      => "ε",
    "и"      => "и",
    "й"      => "ņ",
    "к"      => "ʞ",
    "л"      => "v",
    "м"      => "w",
    "н"      => "н",
    "о"      => "о",
    "п"      => "u",
    "р"      => "d",
    "с"      => "ɔ",
    "т"      => "ɯ", // ʟ ɯ ￌ
    "у"      => "ʎ",
    "ф"      => "ф",
    "х"      => "х",
    "ц"      => "ǹ",
    "ч"      => "Һ",
    "ш"      => "m",
    "щ"      => "m",
    "ъ"      => "q",
    "ы"      => "ıq",
    "ь"      => "q",
    "э"      => "є",
    "ю"      => "oı",
    "я"      => "ʁ",
  ];

  public function onPublicText( Update $update )
  {
    if ($update->message()->text()->trigger() === 'flip')
    {
      $text = $update->message()->text()->token(1);
      $update->replyMessage( self::flip($text));
      return false;
    }
  }

  /**
   * Flip text
   * @param string $text
   *
   * @return string
   */
  public static function flip($text) : string
  {
    // split unicode string
    $text = preg_split('//u', mb_strtolower( $text ));

    // flip with normal table
    $result1 = array_map(function ($char){
      return strtr($char, self::$table);
    }, $text );

    // flip with reversed+flipped table
    $result2 = array_map(function ($char){
      return strtr($char, array_reverse(array_flip(self::$table)));
    }, $text );

    // find best result
    $best = count( array_diff($text, $result1) ) > count(array_diff($text, $result2)) ? $result1 : $result2;

    // reverse and return sting
    return implode("", array_reverse($best));
  }

}