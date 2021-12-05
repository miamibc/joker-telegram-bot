<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Flip;
use PHPUnit\Framework\TestCase;

class FlipTest extends TestCase
{

  public function testFlip()
  {
    $this->assertSame("ʁɔvʎнdǝʚǝdǝu dиw", Flip::flip("мир перевернулся"));
    $this->assertSame("мир перевернулся", Flip::flip("ʁɔvʎнdǝʚǝdǝu dиw"));

    $this->assertSame("˙ʇ,uop uoʎ ʇɐɥʍ uɹɐǝl ˙uɐɔ uoʎ ʇɐɥʍ ʍoɥs", Flip::flip("Show what you can. Learn what you don't."));
    $this->assertSame("show whаt уoп сап. lеагп whаt уoп doп't.", Flip::flip("˙ʇ,uop uoʎ ʇɐɥʍ uɹɐǝl ˙uɐɔ uoʎ ʇɐɥʍ ʍoɥs"));

    // @TODO don't lowercase
    $this->assertSame('ʁɔvʎнdǝʚǝdǝu dиw', Flip::flip('Мир перевернулся'));


  }
}
