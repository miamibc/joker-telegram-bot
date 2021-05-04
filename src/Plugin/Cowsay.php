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
use Joker\Plugin;
use Joker\Event;

class Cowsay extends Plugin
{

  /**
   * CowsayPlugin constructor.
   *
   * @param array $options
   *   font_file - default depends from ubuntu version
   *   font_size - default 20
   *   padding - default 5 x font_size
   *   bg_color - default #000000
   *   text_color - default #ffffff
   *   delete - default true, delete generated image after sending
   */
  public function __construct($options = [])
  {
    if (!isset($options['font_file']))
    {
      // find default font file.  Ubuntu 16 and 20 has different paths
      if (file_exists($path = '/usr/share/fonts/truetype/ubuntu-font-family/UbuntuMono-R.ttf'))
        $options['font_file'] = $path;
      elseif (file_exists($path = '/usr/share/fonts/truetype/ubuntu/UbuntuMono-R.ttf' ))
        $options['font_file'] = $path;
    }

    parent::__construct($options);
  }

  public function onPublicText( Event $event )
  {

    $message_text = $event->message()->text();

    if (!preg_match('@^([\/!](\w+)say)\s?(.*)$@ui', $message_text, $matches)) return;

    $trigger = trim( $matches[1] ); // !cowsay
    $animal  = ucfirst(trim( $matches[2] )); // Cow
    $message = trim( $matches[3] ); // text

    if (in_array($animal, ['Cow', 'Dragon', 'Tux', 'Whale']))
      $class = '\\Cowsayphp\\Farm\\' . $animal;
    else
      $class = '\\Joker\\Animal\\'. $animal;

    if (!class_exists( $class )) return;

    if (!$message)
    {
      $event->answerMessage("Usage: $trigger text");
      return false;
    }

    // create text version
    $animal = Farm::create( $class );
    $result = $animal->say( $message );

    // create image version
    $image  = $this->createImage( $result );

    // send photo and remove
    $event->answerPhoto( $image );

    // delete photo after
    if ( $this->getOption('delete', true) ) unlink($image);

    return false;
  }


  private function createImage( $text )
  {
    $font_file = $this->getOption('font_file');
    $font_size = $this->getOption('font_size', 20);
    $padding   = $this->getOption('padding', $font_size*5);

    // retrieve image size, needed for our message
    $bounding_box = imagettfbbox( $font_size , 0, $font_file, $text );

    // Determine image width and height
    $image_width = abs($bounding_box[4] - $bounding_box[0]) + $padding*2;
    $image_height = abs($bounding_box[5] - $bounding_box[1]) + $padding*2;

    $image = imagecreatetruecolor($image_width, $image_height);

    // draw background
    $rgb = $this->hex2rgb( $this->getOption( 'bg_color', '#000000'));
    $bg_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    imagefill($image, 0, 0, $bg_color);

    // draw text
    $rgb = $this->hex2rgb( $this->getOption( 'text_color', '#ffffff'));
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