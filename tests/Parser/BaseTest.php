<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Bot;
use Joker\Parser\Chat;
use Joker\Parser\Message;
use Joker\Parser\MessageEntity;
use Joker\Parser\PhotoSize;
use Joker\Parser\Text;
use Joker\Parser\Update;
use Joker\Parser\User;
use PHPUnit\Framework\TestCase;

/**
 * Parser\Base: wrapping, arrays, cache, parents
 */
class BaseTest extends TestCase
{

  private function message( $extra = [] )
  {
    return new Message( array_merge([
      'message_id' => 7,
      'date' => 1700000000,
      'chat' => ['id' => -100, 'type' => 'supergroup', 'title' => 'Chat'],
      'from' => ['id' => 42, 'is_bot' => false, 'first_name' => 'Joker'],
      'text' => 'hello world',
      'photo' => [
        ['file_id' => 'a', 'file_unique_id' => 'a1', 'width' => 90, 'height' => 90],
        ['file_id' => 'b', 'file_unique_id' => 'b1', 'width' => 800, 'height' => 800],
      ],
      'entities' => [['type' => 'bold', 'offset' => 0, 'length' => 5]],
    ], $extra), new Bot(false));
  }

  public function testScalarsAreReturnedAsIs()
  {
    $message = $this->message();
    $this->assertSame( 7, $message->message_id() );
    $this->assertSame( 1700000000, $message->date() );
    $this->assertSame( -100, $message->chat()->id() );
    $this->assertSame( 'supergroup', $message->chat()->type() );
  }

  public function testMissingFieldIsFalse()
  {
    $message = $this->message();
    $this->assertFalse( $message->caption() );
    $this->assertFalse( $message->reply_to_message() );
    $this->assertFalse( $message->no_such_field() );
    $this->assertFalse( (new Message(null))->text() );
  }

  public function testObjectIsWrapped()
  {
    $message = $this->message();
    $this->assertInstanceOf( Chat::class, $message->chat() );
    $this->assertInstanceOf( User::class, $message->from() );
    $this->assertInstanceOf( Text::class, $message->text() );
    $this->assertSame( 'hello world', (string) $message->text() );
  }

  public function testArrayOfObjectsIsWrapped()
  {
    $photos = $this->message()->photo();
    $this->assertIsArray( $photos );
    $this->assertCount( 2, $photos );
    $this->assertContainsOnlyInstancesOf( PhotoSize::class, $photos );
    $this->assertSame( 'b', $photos[1]->file_id() );
    $this->assertContainsOnlyInstancesOf( MessageEntity::class, $this->message()->entities() );
  }

  public function testEmptyArrayStaysEmpty()
  {
    $this->assertSame( [], $this->message(['photo' => []])->photo() );
  }

  public function testWrappedObjectsAreCached()
  {
    $message = $this->message();
    $this->assertSame( $message->chat(), $message->chat() );
    $this->assertSame( $message->photo(), $message->photo() );
  }

  public function testParentsAndBot()
  {
    $bot = new Bot(false);
    $update = new Update(['update_id' => 1, 'message' => [
      'message_id' => 1, 'chat' => ['id' => 1, 'type' => 'private'],
      'reply_to_message' => ['message_id' => 0, 'chat' => ['id' => 1, 'type' => 'private'], 'from' => ['id' => 5, 'first_name' => 'A']],
    ]], $bot);

    $user = $update->message()->reply_to_message()->from();
    $this->assertSame( $bot, $user->bot() );
    $this->assertSame( $update->message()->reply_to_message(), $user->parent() );
    $this->assertSame( $update, $user->parent()->parent()->parent() );

    // no bot in parents
    $this->assertFalse( (new Message(['chat' => ['id' => 1]]))->chat()->bot() );
  }

  public function testGetDataAndWrapIn()
  {
    $data = ['id' => 42, 'first_name' => 'Joker'];
    $chat = new Chat( $data );
    $this->assertSame( $data, $chat->getData() );

    $user = $chat->wrapIn( User::class );
    $this->assertInstanceOf( User::class, $user );
    $this->assertSame( 42, $user->id() );
    $this->assertSame( $chat, $user->parent() );
  }

}
