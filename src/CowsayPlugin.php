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

namespace Joker;

use Cowsayphp\Farm;

class CowsayPlugin extends Plugin
{

  private $cow;

  /**
   * CowsayPlugin constructor.
   *
   * @param array $options
   *   font_file - default /usr/share/fonts/truetype/ubuntu-font-family/UbuntuMono-R.ttf
   *   font_size - default 20
   *   padding - default 20
   *   bg_color - default #000000
   *   text_color - default #ffffff
   */
  public function __construct($options = [])
  {
    parent::__construct($options);
  }

  public function onPublicText( Event $event )
  {

    $message_text = $event->getMessageText();

    if (!preg_match('@^([\/!](\w+)say)\s?(.*)$@ui', $message_text, $matches)) return;

    $trigger = trim( $matches[1] ); // !cowsay
    $animal  = trim( $matches[2] ); // cow
    $message = trim( $matches[3] ); // text

    $class = '\Cowsayphp\Farm\\' . ucfirst($animal);

    if (!class_exists( $class )) return;

    if (!$message)
    {
      $event->answerMessage("Usage: $trigger text");
      return false;
    }

    $animal = Farm::create( $class );
    $result = $animal->say( $message );

    $image  = $this->createImage( $result );
    if ( $event->answerPhoto( $image ) ) unlink($image);
    return false;
  }


  private function createImage( $text )
  {
    $font_file = $this->getOption('font_file', '/usr/share/fonts/truetype/ubuntu-font-family/UbuntuMono-R.ttf');
    $font_size = $this->getOption('font_size', 20);
    $padding   = $this->getOption('padding', $font_size*5);

    // retrieve image size, needed for our message
    $bounding_box = imagettfbbox( $font_size , 0, $font_file, $text );

    // Determine image width and height, padding is not needed
    $image_width = abs($bounding_box[4] - $bounding_box[0]) + $padding*2;
    $image_height = abs($bounding_box[5] - $bounding_box[1]) + $padding*2;

    $image = imagecreatetruecolor($image_width, $image_height);

    $rgb = $this->hex2rgb( $this->getOption( 'bg_color', '#000000'));
    $bg_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    imagefill($image, 0, 0, $bg_color);

    $rgb = $this->hex2rgb( $this->getOption( 'text_color', '#ffffff'));
    $text_color = imagecolorallocate($image, $rgb[0], $rgb[1], $rgb[2]);
    imagettftext($image, $font_size, 0, $padding, $padding, $text_color, $font_file, $text);

    $filename = tempnam( 'tmp', 'cowsay');
    imagepng($image, $filename );
    imagedestroy($image);

    return $filename;
  }

  private function hex2rgb( $hex )
  {
    return sscanf($hex, "#%02x%02x%02x");
  }

}