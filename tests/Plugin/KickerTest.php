<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Kicker;
use PHPUnit\Framework\TestCase;

class KickerTest extends TestCase
{

  public function testContainsEmoji()
  {
    $this->assertFalse( Kicker::containsEmoji('') );
    $this->assertFalse( Kicker::containsEmoji('abc def ghi jkl mno pqrs tuv wxyz ABC DEF GHI JKL MNO PQRS TUV WXYZ !"§ $%& /() =?* \'<> #|; ²³~ @`´ ©«» ¤¼× {} abc def ghi jkl mno pqrs tuv wxyz ABC DEF GHI JKL MNO PQRS TUV WXYZ !"§ $%& /() =?* \'<> #|; ²³~ @`´ ©«» ¤¼× {} ') );

    $this->assertTrue(  Kicker::containsEmoji('ab🚟cd') );
    $this->assertTrue(  Kicker::containsEmoji('ab🧦cd') );
    $this->assertTrue(  Kicker::containsEmoji('ab👉cd') );
    $this->assertTrue(  Kicker::containsEmoji('ab👉🏿cd') );
    $this->assertTrue(  Kicker::containsEmoji("a\nb\n👉🏿cd") );
  }
}
