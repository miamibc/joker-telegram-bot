<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Bot;

use Joker\Bot;
use Joker\BotApi;
use Joker\Parser\Base;
use PHPUnit\Framework\TestCase;

/**
 * Consistency of generated code (tools/generate-bot-api.py): run after regeneration
 */
class IntegrityTest extends TestCase
{

  /**
   * @return string[] parser class names
   */
  public function parsers()
  {
    $result = [];
    foreach (glob(__DIR__ . '/../../src/Parser/*.php') as $file)
    {
      $name = basename($file, '.php');
      $result[$name] = ['Joker\\Parser\\' . $name];
    }
    return $result;
  }

  /**
   * @dataProvider parsers
   */
  public function testParserLoadsAndWrapsExistingClasses( string $class )
  {
    $this->assertTrue( class_exists($class), "$class not found" );
    if (in_array($class, ['Joker\\Parser\\Text', 'Joker\\Parser\\Base'])) return; // not API types

    $this->assertTrue( is_subclass_of($class, Base::class), "$class must extend Base" );

    $property = (new \ReflectionClass($class))->getProperty('wrapper');
    $property->setAccessible(true);
    foreach ($property->getValue(new $class([])) as $field => $wrapper)
    {
      $this->assertTrue( class_exists($wrapper), "$class::$field wraps into missing $wrapper" );
    }
  }

  public function testBotApiResultClassesExist()
  {
    $source = file_get_contents( (new \ReflectionClass(BotApi::class))->getFileName() );
    preg_match_all('/apiCall\( \'(\w+)\', .*?(?:, (\w+)::class)?(?:, true)? \);/', $source, $matches, PREG_SET_ORDER);
    $this->assertGreaterThan( 100, count($matches), 'BotApi methods not found' );
    foreach ($matches as $match)
    {
      if (empty($match[2])) continue;
      $this->assertTrue( class_exists('Joker\\Parser\\' . $match[2]), "{$match[1]} returns missing {$match[2]}" );
    }
  }

  public function testBotApiMethodsArePublicAndCallApiWithSameName()
  {
    $source = file_get_contents( (new \ReflectionClass(BotApi::class))->getFileName() );
    preg_match_all('/public function (\w+)\(.*?\n.*?\n    return \$this->apiCall\( \'(\w+)\'/', $source, $matches, PREG_SET_ORDER);
    $this->assertGreaterThan( 100, count($matches) );
    foreach ($matches as $match)
    {
      $this->assertSame( $match[1], $match[2], "{$match[1]}() calls API method {$match[2]}" );
      $this->assertTrue( method_exists(Bot::class, $match[1]) );
    }
  }

  public function testBotMethodsOverrideTraitWithoutConflicts()
  {
    // methods written by hand in Bot are not generated again
    $bot = new \ReflectionClass(Bot::class);
    foreach (['sendMessage', 'sendPhoto', 'getUpdates', 'sendInvoice'] as $method)
    {
      $this->assertSame( Bot::class, $bot->getMethod($method)->getDeclaringClass()->getName() );
      $this->assertStringNotContainsString( "function $method(", file_get_contents((new \ReflectionClass(BotApi::class))->getFileName()) );
    }
  }

}
