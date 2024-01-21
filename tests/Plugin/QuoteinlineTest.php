<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 *
 */

namespace Tests\Plugin;

use Joker\Parser\Update;
use Joker\Plugin\Quote;
use Joker\Plugin\QuoteInline;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-from-github-test
 */
class QuoteinlineTest extends TestCase
{

  public function testHighlight()
  {
    $plugin = new QuoteInline();
    $this->assertEquals('abcdefg hi…' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'bcd', 10));
    $this->assertEquals('abcdefg hi…' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'abcdefg', 10));
    $this->assertEquals('…ij klmn op…' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'klmn', 10));
    $this->assertEquals('…fg hij klm…' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'i love it', 10));
    $this->assertEquals('… opqrstu v…' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'opqrstu vw xyz', 10)); // search by longer word first
    $this->assertEquals('… opqrstu v…' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'vw xyz opqrstu', 10)); // search by longer word first
    $this->assertEquals('…stu vw xyz' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'ofiget vw xyz', 10)); // first highlight word is not found, found by next
    $this->assertEquals('…stu vw xyz' , $plugin->shortenStringHighlighted('abcdefg hij klmn opqrstu vw xyz', 'z', 10)); // first highlight word is not found, found by next
  }

}
