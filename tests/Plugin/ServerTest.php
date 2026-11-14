<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */


namespace Tests\Plugin;

use Joker\Bot;
use Joker\Parser\Update;
use Joker\Plugin\Server;
use PHPUnit\Framework\TestCase;

class ServerTest extends TestCase
{

  const PORT = 55661;

  /** bot without Telegram, remembers requests */
  private function bot()
  {
    return new class(false) extends Bot {
      public $requests = [];
      public function customRequest( $method, $data ) { $this->requests[] = [$method, $data]; return ['ok' => $method]; }
      public function console( $message ) { return $message; }
    };
  }

  public function testDestructWithoutInit()
  {
    $server = new Server(['port' => self::PORT]);
    unset($server);
    $this->assertTrue(true);
  }

  public function testPortBusy()
  {
    $bot = $this->bot();
    $first  = (new Server(['port' => self::PORT]))->setBot($bot); $first->init();
    $second = (new Server(['port' => self::PORT]))->setBot($bot); $second->init();

    // second server can't listen, but works without errors
    $this->assertNull( $second->onEmpty( new Update(null, $bot) ) );
    unset($first, $second);
  }

  public function testRequest()
  {
    $bot = $this->bot();
    $server = (new Server(['port' => self::PORT]))->setBot($bot);
    $server->init();

    // nothing to accept
    $server->onEmpty( new Update(null, $bot) );
    $this->assertSame([], $bot->requests);

    // http request
    $client = stream_socket_client('tcp://127.0.0.1:' . self::PORT, $errno, $error, 2);
    fwrite($client, "POST /sendDice HTTP/1.1\r\nHost: localhost\r\n\r\n" . '{"chat_id":1}');
    usleep(100000);
    $server->onEmpty( new Update(null, $bot) );
    $response = stream_get_contents($client);
    fclose($client);

    $this->assertSame([['sendDice', ['chat_id' => 1]]], $bot->requests);
    $this->assertStringEndsWith('{"ok":"sendDice"}', $response);

    // connection without data is closed without request
    $client = stream_socket_client('tcp://127.0.0.1:' . self::PORT, $errno, $error, 2);
    fclose($client);
    usleep(100000);
    $server->onEmpty( new Update(null, $bot) );
    $this->assertCount(1, $bot->requests);
  }

}
