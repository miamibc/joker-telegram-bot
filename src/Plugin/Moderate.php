<?php
/**
 * Joker Moderator Plugin
 *   Moderates channel or group - removes stickers flood
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Moderate extends Base
{

  protected $options = [
    'characters_between' => 255,

    'description' => 'Moderate plugin',
    'risk' => 'LOW. Nothing stored by plugin',
  ];

  private $counter = [];

  /**
   * Listen to public text message and increase counter
   *
   * @param Update $update
   */
  public function onPublicText( Update $update )
  {
    $id = $update->message()->from()->id();

    // if no counter yet, create it with normal number of messages
    if (!isset( $this->counter[ $id ] ))
      $this->counter[ $id ] = $this->getOption('characters_between');

    $length = mb_strlen($update->message()->text() , 'utf-8' );

    // for cheaters like edson
    if ($length > $this->getOption('characters_between'))
      $length = mt_rand(0, round( $this->getOption('characters_between')/3) );

    $this->counter[ $id ] += $length;
  }

  /**
   * Listen to public sticker and delete it, if counter less than allowed
   *
   * @param Update $update
   *
   * @return int|void
   */
  public function onPublicSticker( Update $update )
  {
    $message = $update->message();

    $id   = $message->from()->id();
    $name = $message->from()->name();

    // if no counter yet, create it with normal number of messages
    if (!isset( $this->counter[ $id ] ))
      $this->counter[ $id ] = $this->getOption('characters_between');

    // sticker flood
    if ($this->counter[ $id ] < $this->getOption('characters_between'))
    {
      // delete it
      $update->deleteMessage();

      // say something
      $need = $this->getOption('characters_between') - $this->counter[ $id ];
      $answer = [
        "Can't post this sh#t righ now, $name. Need $need more chars to post sticker.",
        "$name, you're little damn flooder. Need $need more chars to post sticker.",
        "No sh1t, $name m4n. Need $need more chars to post sticker.",
      ];
      $update->answerMessage( $answer[ array_rand($answer) ]);
    }
    else
    {
      // ok, reset counter
      $this->counter[ $id ] = 0;
    }

  }

}