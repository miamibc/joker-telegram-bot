<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Parser\Text;
use PHPUnit\Framework\TestCase;

class TextTest extends TestCase
{

  public function testLine()
  {
    $text = new Text("abc\ndef\nghi");

    $this->assertEquals("abc\ndef\nghi", $text->line(0));
    $this->assertEquals("def\nghi", $text->line(1));
    $this->assertEquals("def", $text->line(1,1));


    $text = new Text("!meme blb\nlol\nrofl");

    $this->assertEquals("meme", $text->trigger(0));
    $this->assertEquals("blb", $text->token(1,1));
    $this->assertEquals("lol\nrofl", $text->line(1));




  }

}
