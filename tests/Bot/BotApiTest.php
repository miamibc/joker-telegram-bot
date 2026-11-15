<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Bot;

use Joker\Parser\ChatMember;
use Joker\Parser\Message;
use Joker\Parser\MessageId;
use Joker\Parser\StarAmount;
use Joker\Parser\Sticker;
use PHPUnit\Framework\TestCase;

/**
 * Requests made by Bot API methods and wrapping of results
 */
class BotApiTest extends TestCase
{

  const MESSAGE = ['message_id' => 5, 'date' => 1, 'chat' => ['id' => 42, 'type' => 'private']];

  public function testRequiredArgumentsAndOptions()
  {
    $bot = new FakeBot();
    $bot->sendDice( 42, ['emoji' => '🎯', 'disable_notification' => true] );
    $this->assertSame( ['sendDice', ['chat_id' => 42, 'emoji' => '🎯', 'disable_notification' => true], false], $bot->last() );

    $bot->sendLocation( 42, 59.43, 24.75 );
    $this->assertSame( ['sendLocation', ['chat_id' => 42, 'latitude' => 59.43, 'longitude' => 24.75], false], $bot->last() );

    // method without parameters
    $bot->logOut();
    $this->assertSame( ['logOut', [], false], $bot->last() );
  }

  public function testChatIdCanBeSkipped()
  {
    // chat_id of send* methods is optional only for ephemeral replies, null skips it
    $bot = new FakeBot();
    $bot->sendDocument( null, 'file-id', ['ephemeral_message_parameters' => ['callback_query_id' => 'q']] );
    $this->assertSame( ['sendDocument', ['document' => 'file-id', 'ephemeral_message_parameters' => ['callback_query_id' => 'q']], false], $bot->last() );
  }

  public function testOptionsDoNotOverrideNameOfRequiredParameter()
  {
    // sendPoll has required parameter "options", optional ones go to $params
    $bot = new FakeBot();
    $bot->sendPoll( 42, 'Pizza?', [['text' => 'Yes'], ['text' => 'No']], ['is_anonymous' => false] );
    [$method, $data] = $bot->last();
    $this->assertSame( 'sendPoll', $method );
    $this->assertSame( [['text' => 'Yes'], ['text' => 'No']], $data['options'] );
    $this->assertFalse( $data['is_anonymous'] );
  }

  public function testResultIsWrapped()
  {
    $bot = new FakeBot();
    $bot->responses['sendDice'] = self::MESSAGE;
    $message = $bot->sendDice( 42 );
    $this->assertInstanceOf( Message::class, $message );
    $this->assertSame( 5, $message->message_id() );
    $this->assertSame( $bot, $message->bot() );

    $bot->responses['getChatMember'] = ['status' => 'member', 'user' => ['id' => 7]];
    $this->assertInstanceOf( ChatMember::class, $member = $bot->getChatMember( 42, 7 ) );
    $this->assertSame( 7, $member->user()->id() );

    $bot->responses['copyMessage'] = ['message_id' => 9];
    $this->assertInstanceOf( MessageId::class, $bot->copyMessage( 42, 43, 5 ) );
  }

  public function testArrayResultIsWrapped()
  {
    $bot = new FakeBot();
    $bot->responses['getCustomEmojiStickers'] = [['file_id' => 'a'], ['file_id' => 'b']];
    $stickers = $bot->getCustomEmojiStickers( ['1', '2'] );
    $this->assertCount( 2, $stickers );
    $this->assertContainsOnlyInstancesOf( Sticker::class, $stickers );
    $this->assertSame( 'b', $stickers[1]->file_id() );

    $bot->responses['sendMediaGroup'] = [self::MESSAGE, self::MESSAGE];
    $this->assertContainsOnlyInstancesOf( Message::class, $bot->sendMediaGroup( 42, [] ) );
  }

  public function testScalarAndErrorResults()
  {
    $bot = new FakeBot();
    $this->assertTrue( $bot->setMessageReaction( 42, 5 ) );

    $bot->responses['getChatMemberCount'] = 12;
    $this->assertSame( 12, $bot->getChatMemberCount( 42 ) );

    $bot->responses['exportChatInviteLink'] = 'https://t.me/+abc';
    $this->assertSame( 'https://t.me/+abc', $bot->exportChatInviteLink( 42 ) );

    // edit of inline message returns true instead of Message
    $bot->responses['editMessageText'] = true;
    $this->assertTrue( $bot->editMessageText( ['inline_message_id' => 'x', 'text' => 'new'] ) );

    // API error
    $bot->responses['sendDice'] = false;
    $this->assertFalse( $bot->sendDice( 42 ) );
  }

  public function testFileUploadIsMultipart()
  {
    $bot = new FakeBot();
    $file = new \CURLFile( __FILE__ );
    $bot->sendDocument( 42, $file, ['caption' => 'test', 'disable_notification' => true, 'reply_markup' => ['inline_keyboard' => []]] );
    [$method, $data, $multipart] = $bot->last();

    $this->assertSame( 'sendDocument', $method );
    $this->assertTrue( $multipart );
    $this->assertSame( $file, $data['document'] );
    $this->assertSame( 42, $data['chat_id'] );
    $this->assertSame( 'true', $data['disable_notification'] );           // booleans as strings
    $this->assertSame( '{"inline_keyboard":[]}', $data['reply_markup'] ); // arrays as JSON
  }

  public function testFileIdIsNotMultipart()
  {
    $bot = new FakeBot();
    $bot->sendDocument( 42, 'file-id', ['disable_notification' => true] );
    $this->assertSame( ['sendDocument', ['chat_id' => 42, 'document' => 'file-id', 'disable_notification' => true], false], $bot->last() );
  }

  public function testHandWrittenMethods()
  {
    $bot = new FakeBot();
    $bot->responses['sendMessage'] = self::MESSAGE;
    $this->assertInstanceOf( Message::class, $bot->sendMessage( 42, 'hi', ['parse_mode' => 'HTML'] ) );
    $this->assertSame( ['sendMessage', ['chat_id' => 42, 'text' => 'hi', 'parse_mode' => 'HTML'], false], $bot->last() );

    $bot->sendInvoice( 42, 'T', 'D', 'p', 'XTR', [['label' => 'L', 'amount' => 5]] );
    $this->assertSame( 'sendInvoice', $bot->last()[0] );
    $this->assertSame( 'XTR', $bot->last()[1]['currency'] );

    $this->assertTrue( $bot->answerPreCheckoutQuery( 'q', false, 'sorry' ) );
    $this->assertSame( ['answerPreCheckoutQuery', ['pre_checkout_query_id' => 'q', 'ok' => false, 'error_message' => 'sorry'], false], $bot->last() );

    $bot->responses['getMyStarBalance'] = ['amount' => 100];
    $this->assertInstanceOf( StarAmount::class, $balance = $bot->getMyStarBalance() );
    $this->assertSame( 100, $balance->amount() );
  }

}
