<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */


namespace Tests\Parser;

use Joker\Bot;
use Joker\Parser\ChatMember;
use Joker\Parser\ChatMemberUpdated;
use Joker\Parser\Message;
use Joker\Parser\MessageReactionUpdated;
use Joker\Parser\ReactionType;
use Joker\Parser\Text;
use Joker\Parser\Update;
use PHPUnit\Framework\TestCase;

class ApiTypesTest extends TestCase
{

  public function testReaction()
  {
    $update = new Update(['update_id' => 1, 'message_reaction' => [
      'chat' => ['id' => 1, 'type' => 'group'], 'message_id' => 5, 'date' => 1,
      'old_reaction' => [],
      'new_reaction' => [['type' => 'emoji', 'emoji' => '👍'], ['type' => 'paid']],
    ]], new Bot(false));
    $this->assertInstanceOf( MessageReactionUpdated::class, $reaction = $update->message_reaction() );
    $this->assertCount( 2, $types = $reaction->new_reaction() );
    $this->assertInstanceOf( ReactionType::class, $types[0] );
    $this->assertSame( '👍', $types[0]->emoji() );
    $this->assertSame( 'paid', $types[1]->type() );
    $this->assertInstanceOf( Bot::class, $types[0]->bot() );
  }

  public function testChatMember()
  {
    $update = new Update(['update_id' => 2, 'chat_member' => [
      'chat' => ['id' => 1, 'type' => 'group'], 'from' => ['id' => 2], 'date' => 1,
      'old_chat_member' => ['status' => 'left', 'user' => ['id' => 3]],
      'new_chat_member' => ['status' => 'administrator', 'user' => ['id' => 3], 'can_delete_messages' => true],
    ]]);
    $this->assertInstanceOf( ChatMemberUpdated::class, $member = $update->chat_member() );
    $this->assertInstanceOf( ChatMember::class, $member->new_chat_member() );
    $this->assertTrue( $member->new_chat_member()->can_delete_messages() );
    $this->assertSame( 3, $member->new_chat_member()->user()->id() );
  }

  public function testMessageKeepsCustomWrappers()
  {
    $update = new Update(['update_id' => 3, 'message' => [
      'message_id' => 1, 'chat' => ['id' => 1, 'type' => 'private'], 'text' => '/sponsor',
      'reply_to_message' => ['message_id' => 0, 'chat' => ['id' => 1, 'type' => 'private'], 'caption' => 'hi'],
    ]]);
    $this->assertInstanceOf( Text::class, $update->message()->text() );
    $this->assertSame( 'sponsor', $update->message()->text()->trigger() );
    $this->assertInstanceOf( Message::class, $reply = $update->message()->reply_to_message() );
    $this->assertInstanceOf( Text::class, $reply->caption() );
  }

}
