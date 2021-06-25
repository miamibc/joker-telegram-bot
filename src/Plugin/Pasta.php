<?php
/**
 * Pasta plugin for Joker
 *
 * Reacts to some words with a pasta
 * @see http://www.bolshoyvopros.ru/questions/2822384-chto-takoe-pasta-na-molodjozhnom-slengekakovo-proishozhdenie-slova.html
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Pasta extends Base
{

  private $last_message = 0;

  public function onPublicText( Update $update )
  {

    $text = $update->message()->text();

    // once in hour
    if (time() < $this->last_message + $this->getOption('minimum_time', 60*60)) return;

    $answer = false;
    if (preg_match('@\b(кофе|кофеёк|кофейный)\b@ui',$text,$matches))
    {
      $answer = "Давно не пил кофе, сегодня навел кофе, Jacobs Monarch. Блин пью с удовольствием. Можно так сказать, что я кофе-ман, очень сильно люблю, хорошее качественное, кофе.";
    }
    if (preg_match('@\b(чай|чаёк|чайный|чая|чаю)\b@ui',$text,$matches))
    {
      $answer = "Сейчас навёл себе чай Tetley, можно скачать что я чае-ман - люблю качественный чай))";
    }

    if ($answer)
    {
      $this->last_message = time();
      $update->answerMessage($answer);
      return false;
    }

  }

  public function onPublicPhotoCaption( Update $update )
  {
    return $this->onPublicText($update);
  }



}