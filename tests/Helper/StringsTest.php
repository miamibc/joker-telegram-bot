<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Helper;

use PHPUnit\Framework\TestCase;
use Joker\Helper\Strings;

class StringsTest extends TestCase
{

  public function testDiffTimeInWords()
  {
    $this->assertEquals("18 hours 11 minutes 9 seconds", Strings::diffTimeInWords("2023-12-16T07:51:57Z", 1702778586));
    $this->assertEquals("18 hours 11 minutes 9 seconds", Strings::diffTimeInWords(1702713117, 1702778586));
  }
  public function testTransliterate()
  {
    $this->assertEquals("AbcDefGzhZ", Strings::transliterate("АбцДефГжЗ"));
    $this->assertEquals("AbcDefG", Strings::transliterate("АбцДефГ", 'cyr', 'lat'));
    $this->assertEquals("АбцДефГ", Strings::transliterate("АбцДефГ", 'lat', 'cyr'));
    $this->assertEquals("АбцДефГ", Strings::transliterate("AbcDefG", 'lat', 'cyr'));
    $this->assertEquals("AbcDefG", Strings::transliterate("AbcDefG", 'lat', 'lat'));
    $this->assertEquals("АбцДефГ", Strings::transliterate("АбцДефГ", 'lat', 'lat'));
    $this->assertEquals("AbcDefG", Strings::transliterate("AbcDefG", 'cyr', 'cyr'));
    $this->assertEquals("АбцДефГ", Strings::transliterate("АбцДефГ", 'cyr', 'cyr'));
  }
}
