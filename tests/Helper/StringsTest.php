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

}
