<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 *
 */

namespace Tests\Plugin;

use Joker\Parser\Update;
use Joker\Plugin\Quote;
use PHPUnit\Framework\TestCase;

/**
 * @group exclude-from-github-test
 */
class QuoteTest extends TestCase
{

  public function testTelegramQuoteConverter()
  {
    $text = <<<EOF
Artyom Lukin:
кто чо делает в этот прекрасный вечер?)))

Viktor Overdoze:
Фейерверки смотрим

Хуле еще делать

Sergei Miami:
Фейверки смотрю. Хуль ещё делать

Eduard Zagrijev:
Фейверки смотрю. Хуль ещё делать

Artyom Lukin:
Фейверки смотрю. Хуль ещё делать

И я))

Ilja:
Запустил с сыном сегодня фейерверки

Все посмотрели?
EOF;

    $plugin = new Quote(['dir' => $dir = dirname(__FILE__) . '/../data']);
    $this->assertEquals(null, $plugin->onPrivateText( new Update(['message'=>['text'=>$text]])) );
    $this->assertFileExists( $file = "$dir/!tg.txt");
    $this->assertStringEqualsFile($file, "\n" . '<Artyom Lukin> кто чо делает в этот прекрасный вечер?)))\n<Viktor Overdoze> Фейерверки смотрим\nХуле еще делать\n<Sergei Miami> Фейверки смотрю. Хуль ещё делать\n<Eduard Zagrijev> Фейверки смотрю. Хуль ещё делать\n<Artyom Lukin> Фейверки смотрю. Хуль ещё делать\nИ я))\n<Ilja> Запустил с сыном сегодня фейерверки\nВсе посмотрели?');
    unlink($file);
  }

}
