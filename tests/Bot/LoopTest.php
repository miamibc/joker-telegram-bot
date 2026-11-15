<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Bot;

use Joker\Bot;
use Joker\Parser\Update;
use Joker\Plugin\Base;
use PHPUnit\Framework\TestCase;

/**
 * Bot::loop(): choosing plugin methods by update tags, plugin chain, priorities
 */
class LoopTest extends TestCase
{

  const PRIVATE_TEXT = ['update_id' => 1, 'message' => ['message_id' => 1, 'chat' => ['id' => 42, 'type' => 'private'], 'from' => ['id' => 42], 'text' => 'hi']];
  const GROUP_TEXT   = ['update_id' => 2, 'message' => ['message_id' => 2, 'chat' => ['id' => -1, 'type' => 'supergroup'], 'from' => ['id' => 42], 'text' => 'hi']];

  /**
   * Plugin, that records called methods and returns $result from them
   */
  private function plugin( $result = null )
  {
    return new class($result) extends Base {
      public $calls = [];
      public $inited = false;
      private $result;
      public function __construct( $result ) { parent::__construct(); $this->result = $result; }
      public function init() { $this->inited = true; }
      public function onText( Update $update )        { $this->calls[] = __FUNCTION__; return $this->result; }
      public function onPrivateText( Update $update ) { $this->calls[] = __FUNCTION__; return $this->result; }
      public function onTextGroup( Update $update )   { $this->calls[] = __FUNCTION__; return $this->result; }
      public function onSticker( Update $update )     { $this->calls[] = __FUNCTION__; return $this->result; }
      public function onCheckout( Update $update )    { $this->calls[] = __FUNCTION__; return $this->result; }
      public function onEmpty( Update $update )       { $this->calls[] = __FUNCTION__; return $this->result; }
      public function onUnknownTag( Update $update )  { $this->calls[] = __FUNCTION__; return $this->result; }
      public function helper()                        { $this->calls[] = __FUNCTION__; }
    };
  }

  private function run_loop( array $updates, array $plugins, $times = 1 )
  {
    $bot = new FakeBot();
    $bot->updates = $updates;
    $bot->plug( $plugins );
    for ($i = 0; $i < $times; $i++) $bot->loop();
    return $bot;
  }

  public function testPlugSetsBotAndCallsInit()
  {
    $plugin = $this->plugin();
    $bot = $this->run_loop( [], [$plugin], 0 );
    $this->assertTrue( $plugin->inited );
    $this->assertSame( $bot, $plugin->bot() );
    $this->assertSame( [$plugin], $bot->plugins() );
  }

  public function testMethodsAreChosenByAllTags()
  {
    $plugin = $this->plugin();
    $this->run_loop( [self::PRIVATE_TEXT], [$plugin] );
    $this->assertSame( ['onText', 'onPrivateText'], $plugin->calls );

    $plugin = $this->plugin();
    $this->run_loop( [self::GROUP_TEXT], [$plugin] );
    $this->assertSame( ['onText', 'onTextGroup'], $plugin->calls ); // order of tags in name doesn't matter
  }

  public function testEmptyUpdateWhenNoUpdates()
  {
    $plugin = $this->plugin();
    $this->run_loop( [], [$plugin] );
    $this->assertSame( ['onEmpty'], $plugin->calls );
  }

  public function testOneUpdatePerLoop()
  {
    $plugin = $this->plugin();
    $bot = $this->run_loop( [self::PRIVATE_TEXT, self::GROUP_TEXT], [$plugin], 3 );
    $this->assertSame( ['onText', 'onPrivateText', 'onText', 'onTextGroup', 'onEmpty'], $plugin->calls );

    // next getUpdates asks for updates after the last one
    $requests = array_values(array_filter($bot->requests, function ($r) { return $r[0] === 'getUpdates'; }));
    $this->assertSame( 0, $requests[0][1]['offset'] );
    $this->assertSame( 3, end($requests)[1]['offset'] );
  }

  public function testFalseBreaksPluginChain()
  {
    $first = $this->plugin( Bot::PLUGIN_BREAK ); $second = $this->plugin();
    $this->run_loop( [self::PRIVATE_TEXT], [$first, $second] );
    $this->assertSame( ['onText'], $first->calls );
    $this->assertSame( [], $second->calls );
  }

  public function testTrueGoesToNextPlugin()
  {
    $first = $this->plugin( Bot::PLUGIN_NEXT ); $second = $this->plugin();
    $this->run_loop( [self::PRIVATE_TEXT], [$first, $second] );
    $this->assertSame( ['onText'], $first->calls );           // other methods of this plugin skipped
    $this->assertSame( ['onText', 'onPrivateText'], $second->calls );
  }

  public function testNullCallsNextMethodsAndPlugins()
  {
    $first = $this->plugin( null ); $second = $this->plugin( null );
    $this->run_loop( [self::PRIVATE_TEXT], [$first, $second] );
    $this->assertSame( ['onText', 'onPrivateText'], $first->calls );
    $this->assertSame( ['onText', 'onPrivateText'], $second->calls );
  }

  public function testExceptionBreaksPluginChain()
  {
    $failing = new class extends Base {
      public function onText( Update $update ) { throw new \Exception('plugin failed'); }
    };
    $second = $this->plugin();
    $this->run_loop( [self::PRIVATE_TEXT], [$failing, $second] );
    $this->assertSame( [], $second->calls );
  }

}
