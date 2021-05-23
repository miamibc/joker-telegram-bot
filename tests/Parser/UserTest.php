<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Parser\Chat;
use Joker\Parser\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{

  public function testReWrapping()
  {
    $data = [
      'id' => 123,
      'first_name' => 'Lol',
      'last_name' => 'Rofl',
      'username' => 'lolrofl',
      'title' => 'Title',
    ];
    $user = new User($data);

    $this->assertSame( 123, $user->id());
    $this->assertSame( 'Lol Rofl', $user->name());
    $this->assertSame( 'lolrofl',  $user->username());

    /** @var Chat $chat */
    $this->assertInstanceOf(Chat::class, $chat = $user->wrapIn(Chat::class) );

    $this->assertSame( 123, $chat->id());
    $this->assertSame( 'Title', $chat->name());
    $this->assertSame( 'lolrofl', $chat->username());

    $this->assertNotEquals( "$chat", "$user");

  }
}
