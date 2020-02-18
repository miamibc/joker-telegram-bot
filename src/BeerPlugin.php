<?php
/**
 * Beer plugin for Joker
 *
 * Save water, drink beer!
 *
 * @package joker-telegram-bot
 * @author Dm!tro <dima@aseri.net>
 */

namespace Joker;

class BeerPlugin extends Plugin
{

  private $last_message = 0;

  public function onPublicText( Event $event )
  {

    $text = $event->getMessageText();

    if (time() < $this->last_message + $this->getOption('minimum_time', 60*60)) return; // once in 15 minutes

    $answer = false;

    $beer = array(
      "Пиво — третий по популярности напиток среди жителей планеты (на первом месте вода, на втором чай).",
      "У вкусного напитка пива есть официальное научное название zythology.Оно происходит от греческих слов «Zythos» (пиво) и «Logos» (исследование). ",
      "Хотя происхождение пива можно проследить еще в древнем Вавилоне, но идея деления пива на сорта появилась совершенно недавно, лишь в 1935 году.",
      "Крупнейший в мире Музей пива находится в США в штате Кентукки. Там выставлено более миллиона экспонатов, включая коллекции этикеток, пробок, банок, древнее пивоваренное оборудование.",
      "Очень боюсь расстроить баристу своим неправильным произношением названия кофе, поэтому всегда заказываю пиво.",
      "Попробовал безалкогольное пиво. По вкусу как будто тебя никто не любит.",
      "Романтический коктейль \"69\" Ингредиенты: — Балтика 6, — Балтика 9",
      "Если сам не знаешь, чего хочешь, ты хочешь пивка.",
      "— Ты много пива можешь выпить?, — Это смотря кто покупает.",
      "В сутках 24 часа, в ящике пива 24 бутылки - совпадение?",
      "Рожденный пить не пить не может.",
      "Если пиво ты не любишь, значит жизнь свою ты губишь.",
      "Пиво без водки как паспорт без фотки.",
      "Two beer or not two beer?",
      "Сколько будет пива - решает пиво.",
    );

    if (preg_match('@\b(пиво|пива|пивк|пивчан)@ui',$text,$matches))
    {
        $rand = mt_rand( 0, count( $beer ) - 1 );
        $answer = $beer[$rand];
    }

    if ($answer)
    {
      $this->last_message = time();
      $event->answerMessage($answer);
      return false;
    }

  }

  public function onPublicPhotoCaption( Event $event )
  {
    return $this->onPublicText($event);
  }



}