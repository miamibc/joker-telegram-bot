<?php
/**
 * Whynot plugin for Joker
 * Generates otmazki why not ...
 *
 * Idea from https://github.com/lgg/excuse-generator
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Whynot extends Base
{

  protected $options = [

    "names" => [
      "Друже",
      "Друг",
      "Товарищ",
      "Приятель",
      "Глубокоуважаемый",
    ],

    "hello" => [
      "[name], привет",
      "[name], здравствуй",
      "[name], приветствую",
      "[name], добрый день",
      "[name], добрый вечер",
      "[name], доброе утро",
      "Здравствуй, [name]",
      "Привет, [name]",
    ],

    "fail" => [
      "Вся одежда постирана выйти не в чем",
      "Стирка, уборка глажка. Занят, очень занят",
      "Всю ночь дырочку штукатурил",
      "Я поймал попутку, её остановила ГИБДД, и нашли крупную партию наркотиков. Сейчас я под следствием",
      "Я потерял всё с чем обычно гуляю, поэтому сегодня не пойду",
      "Я улетел на гоа, пью джюс курю трубку",
      "Меня избили цыгане, я стесняюсь выходить",
      "Я не в городе, уехал в тур по Нарнии",
      "Я очень занят очень сложными делами",
      "Меня машина сбила, сейчас выйти не смогу",
      "Во мне внезапно проснулся философ, всё тлен",
      "Меня в армию забрали, встретимся через год",
      "У меня молоко убежало, улетела простыня и подушка как лягушка ускакала от меня",
    ],

    "action" => [
      "Давай",
      "Постараюсь",
      "Смогу",
      "Смогу пойти",
      "Давай пойдём",
      "Доеду до дома",
      "Приеду домой",
      "Попробую",
      "Давай встретимся",
      "Возможно смогу",
    ],

    "date" => [
      "сейчас",
      "завтра",
      "завтра вечером",
      "завтра днем",
      "завтра утром",
      "чуть позже",
      "позже",
      "в конце недели",
      "в конце месяца",
      "в конце дня",
      "до конца следующей недели",
      "послезавтра",
      "ближе к вечеру",
      "ближе к утру",
      "с утра",
      "в крайнем случае завтра",
      "на неделе",
      "через пару дней",
      "скоро",
      "в понедельник",
      "во вторник",
      "после пятницы",
      "когда рак свиснет",
    ],

    "general" => [
      "Я бы с радостью вышел, но увы",
      "Я, конечно, очень извиняюсь, что так вышло...",
      "Извини, до связи",
      "Пока!",
      "До скорого",
      ";-]]]",
    ],


    'description' => 'Generates stupid excuses why not',
    'risk' => 'LOW. Nothing stored by plugin',
  ];

  public function onPublicText( Update $update )
  {

    $text = $update->message()->text();
    if ($text->trigger() !== 'whynot') return;

    $update->answerMessage( $this->generate() );
    return false;
  }

  public function generate( $name = false )
  {
    // if no name is defined, return random
    if (!$name)
    {
      $name = $this->randomPhrase( $this->getOption('names') );
    }

    // generate parts of reply
    $hello   = $this->randomPhrase( $this->getOption('hello'), ['[name]' => $name, ]);
    $fail    = $this->randomPhrase( $this->getOption('fail') );
    $action  = $this->randomPhrase( $this->getOption('action') );
    $date    = $this->randomPhrase( $this->getOption('date') );
    $general = $this->randomPhrase( $this->getOption('general') );
    return "$hello. $fail. $action $date. $general";
  }

  public function randomPhrase( array $items, array $replacements = [])
  {
    $random = mt_rand(0, count($items)-1);
    return strtr( $items[$random], $replacements);
  }

}