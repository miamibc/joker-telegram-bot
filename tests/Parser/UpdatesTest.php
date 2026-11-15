<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Parser;

use Joker\Bot;
use Joker\Parser\Base;
use Joker\Parser\Text;
use Joker\Parser\Update;
use PHPUnit\Framework\TestCase;

/**
 * Typical updates from tests/data/updates: tags, and every field is readable through parsers
 */
class UpdatesTest extends TestCase
{

  /**
   * Fixture => tags expected to be true
   * Note: Private/Public is detected by `message` only, so callbacks, inline queries,
   * edits etc. are Public even in private chats (plugins rely on it)
   */
  const TAGS = [
    'callback'              => ['Public', 'Callback'],
    'callback_inaccessible' => ['Public', 'Callback'],
    'chat_member'           => ['Public'],
    'checkout'              => ['Public', 'Checkout'],
    'edited_private'        => ['Public', 'Edit'],
    'forward'               => ['Public', 'Group', 'Text', 'Forward', 'Message'],
    'group_photo_reply'     => ['Public', 'Group', 'Photo', 'Caption', 'Reply', 'Message'],
    'inline'                => ['Public', 'Inline'],
    'join'                  => ['Public', 'Group', 'Join', 'Message'],
    'payment'               => ['Private', 'Message', 'Payment'],
    'poll'                  => ['Public', 'Poll'],
    'private_command'       => ['Private', 'Entities', 'Text', 'Message'],
    'reaction'              => ['Public'],
    'sticker'               => ['Public', 'Group', 'Sticker', 'Message'],
    'venue'                 => ['Public', 'Group', 'Location', 'Message'],
  ];

  public function fixtures()
  {
    $result = [];
    foreach (glob(__DIR__ . '/../data/updates/*.json') as $file)
      $result[basename($file, '.json')] = [basename($file, '.json'), json_decode(file_get_contents($file), true)];
    return $result;
  }

  public function testEveryFixtureHasExpectedTags()
  {
    $this->assertEqualsCanonicalizing( array_keys(self::TAGS), array_keys($this->fixtures()) );
  }

  /**
   * @dataProvider fixtures
   */
  public function testTags( string $name, array $data )
  {
    $update = new Update( $data, new Bot(false) );
    $this->assertEqualsCanonicalizing( self::TAGS[$name], array_keys(array_filter($update->getTags())) );
    foreach (self::TAGS[$name] as $tag) $this->assertTrue( $update->hasTag($tag) );
  }

  /**
   * @dataProvider fixtures
   */
  public function testAllFieldsAreReadable( string $name, array $data )
  {
    $bot = new Bot(false);
    $update = new Update( $data, $bot );
    $wrapped = $this->walk( $update, $data, 'update', $bot );
    $this->assertGreaterThan( 0, $wrapped, "$name: nothing wrapped" );
  }

  /**
   * Read every field of parser and compare with raw data, go deeper into wrapped objects
   * @return int number of wrapped objects
   */
  private function walk( Base $object, array $data, string $path, Bot $bot ): int
  {
    $wrapped = 0;
    foreach ($data as $key => $value)
    {
      $result = $object->$key();
      $where  = "$path.$key";

      if ($result instanceof Text)
      {
        $this->assertSame( $value, (string) $result, $where );
      }
      elseif ($result instanceof Base)
      {
        // only objects are wrapped into one parser, not lists (even empty)
        $this->assertTrue( is_array($value) && $value !== [] && array_keys($value) !== range(0, count($value) - 1), "$where: list wrapped as object" );
        $this->assertSame( $bot, $result->bot(), "$where has no bot in parents" );
        $wrapped += 1 + $this->walk( $result, $value, $where, $bot );
      }
      elseif (is_array($result) && $result && reset($result) instanceof Base)
      {
        $this->assertCount( count($value), $result, $where );
        foreach ($result as $i => $item)
          $wrapped += 1 + $this->walk( $item, $value[$i], "{$where}[$i]", $bot );
      }
      else
      {
        $this->assertSame( $value, $result, $where );
        // objects of known types must be wrapped
        if (is_array($value) && $value && array_keys($value) !== range(0, count($value) - 1))
          $this->fail( "$where is not wrapped" );
      }
    }
    return $wrapped;
  }

}
