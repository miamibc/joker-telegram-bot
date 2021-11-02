<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Bot;
use Joker\Parser\Chat;
use Joker\Parser\ChatPhoto;
use Joker\Parser\Message;
use Joker\Parser\Update;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{

  public function testParent()
  {
    $bot = new Bot(false);
    $update = new Update(['message' => ['chat' => ['photo' => ['small_file_id' => 'test']]]], $bot);

    $this->assertInstanceOf( Message::class, $element = $update->message() );
    $this->assertInstanceOf( Bot::class, $element->bot() );

    $this->assertInstanceOf( Chat::class, $element = $element->chat() );
    $this->assertInstanceOf( Bot::class, $element->bot() );

    $this->assertInstanceOf( ChatPhoto::class, $element = $element->photo() );
    $this->assertInstanceOf( Bot::class, $element->bot() );

    // back to bot manually
    $this->assertInstanceOf( Chat::class, $element = $element->parent() );
    $this->assertInstanceOf( Message::class, $element = $element->parent() );
    $this->assertInstanceOf( Update::class, $element = $element->parent() );
    $this->assertInstanceOf( Bot::class, $element->parent() );
  }


}
