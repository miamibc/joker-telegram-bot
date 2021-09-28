<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Ytmusic;
use PHPUnit\Framework\TestCase;

class YtmusicTest extends TestCase
{

  public function testLinkToYoutube()
  {
    $this->assertSame('video', Ytmusic::linkToYoutube('youtu.be/video'));
    $this->assertSame('12345', Ytmusic::linkToYoutube('youtu.be/12345'));
    $this->assertSame('12345', Ytmusic::linkToYoutube('https://youtu.be/12345'));
    $this->assertSame('123youtube', Ytmusic::linkToYoutube('https://youtu.be/123youtube'));
    $this->assertSame('123youtube', Ytmusic::linkToYoutube('https://wwww.youtube.com/watch?v=123youtube'));
  }

  public function testSlugify()
  {
    $this->assertSame('my-favourite-video-from-youtube', Ytmusic::slugify('My favourite video from Youtube'));
    $this->assertSame('my-favourite-video-from-youtube', Ytmusic::slugify('My  favourite  video  from  Youtube'));
    $this->assertSame('moye-lyubimoye-video-iz-yutyuba', Ytmusic::slugify('Моё любимое видео из ютюба'));


  }
}
