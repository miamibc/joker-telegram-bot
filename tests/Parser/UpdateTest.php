<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Parser\Chat;
use Joker\Parser\ChatPhoto;
use Joker\Parser\Message;
use Joker\Parser\Update;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{

  public function testParent()
  {
    $update = new Update(['message' => ['chat' => ['photo' => ['small_file_id' => 'test']]]]);
    $this->assertInstanceOf( Message::class, $element = $update->message() );
    $this->assertInstanceOf( Chat::class, $element = $element->chat() );
    $this->assertInstanceOf( ChatPhoto::class, $element = $element->photo() );
    $this->assertInstanceOf( Chat::class, $element = $element->parent() );
    $this->assertInstanceOf( Message::class, $element = $element->parent() );
    $this->assertInstanceOf( Update::class, $element = $element->parent() );
    $this->assertNull( $element->parent() );
  }

}
