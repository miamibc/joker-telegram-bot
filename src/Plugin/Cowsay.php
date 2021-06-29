<?php
/**
 * Cowsay plugin for Joker
 *
 * Classic console fun now is in Joker. Say
 *   !cowsay Moo
 *
 * bot will answer:
 *   < Moo >
 *        \   ^__^
 *         \  (oo)\_______
 *            (__)\       )\/\
 *               ||----w |
 *              ||     ||
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Cowsayphp\Farm;
use Joker\Parser\Update;

class Cowsay extends Base
{

  protected $options = [
    'font_file'  => null,
    'font_size'  => 20,
    'padding'    => 100,
    'bg_color'   => '#000000',
    'text_color' => '#ffffff',
    'delete'     => true,
  ];

  /**
   * CowsayPlugin constructor.
   *
   * Options can be:
   *   `font_file`  (string, optional, default depends on ubuntu version) path to font file
   *   `font_size`  (int, optional, default 20) font size in pixels
   *   `padding`    (int, optional, default 100) padding
   *   `bg_color`   (string, optional, default #000000) background color
   *   `text_color` (string, optional, default #ffffff) text color
   *   `delete`     (boolean, optional, default true) delete generated image after sending
   */
  public function __construct($options = [])
  {
    // if font_file option is not defined
    if (!isset($options['font_file']))
    {
      // try to find font_file in system.  Ubuntu 16 and 20 has different paths
      if (file_exists($path = '/usr/share/fonts/truetype/ubuntu-font-family/UbuntuMono-R.ttf'))
        $options['font_file'] = $path;
      elseif (file_exists($path = '/usr/share/fonts/truetype/ubuntu/UbuntuMono-R.ttf' ))
        $options['font_file'] = $path;
    }

    parent::__construct($options);
  }

  public function onPublicText( Update $update )
  {

    // if no font_file set, don't process request
    // (without font we can't draw image)
    if (!$this->getOption('font_file')) return;

    $text    = $update->message()->text();
    $trigger = $text->trigger();

    // trigger must end with 'say'
    if (substr( $trigger, -3 ) !== 'say') return;

    $animal  = ucfirst( substr($trigger, 0, -3));
    $message = $text->token(1);

    if (in_array($animal, ['Cow', 'Dragon', 'Tux', 'Whale']))
      $class = '\\Cowsayphp\\Farm\\' . $animal;
    else
      $class = '\\Joker\\Animal\\'. $animal;

    if (!class_exists( $class )) return;

    if (!$message)
    {
      $update->answerMessage("Usage: $trigger text");
      return false;
    }

    // create text version
    $animal = Farm::create( $class );
    $result = $animal->say( $message );

    // create image version
    $image  = $this->createImage( $result );

    // send photo and remove
    $update->answerPhoto( $image );

    // delete photo after
    if ( $this->getOption('delete', true) ) unlink($image);

    return false;
  }


  private function createImage( $text )
  {
    $font_file = $this->getOption('font_file');
    $font_size = $this->getOption('font_size');
    $padding   = $this->getOption('padding');

    // retrieve image size, needed for our message
    $bounding_box = imagettfbbox( $font_size , 0, $font_file, $text );

    // Determine image width and height
    $image_width = abs($bounding_box[4] - $bounding_box[0]) + $padding*2;
    $image_height = abs($bounding_box[5] - $bounding_box[1]) + $padding*2;

    $image = imagecreatetruecolor($image_width, $image_height);

    // draw background
    $rgb = $this->hex2rgb( $this->getOption( 'bg_color' ));
    $bg_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    imagefill($image, 0, 0, $bg_color);

    // draw text
    $rgb = $this->hex2rgb( $this->getOption( 'text_color' ));
    $text_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    imagettftext($image, $font_size, 0, $padding, $padding, $text_color, $font_file, $text);

    // save to temp file
    if (!file_exists('data/cowsay')) mkdir('data/cowsay');
    $filename = tempnam( 'data/cowsay', 'image');
    imagepng($image, $filename );
    imagedestroy($image);

    // return filename
    return $filename;
  }

  private function hex2rgb( $hex )
  {
    return sscanf($hex, "#%02x%02x%02x");
  }

}