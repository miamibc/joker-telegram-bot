<?php
/**
 * Excuse plugin for Joker
 *
 * Generate excuses
 * Ported from https://github.com/lgg/excuse-generator
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Excuse extends Base
{

  protected $options = [

    'description' => 'Excuse plugin',
    'risk' => 'LOW. Nothing stored by plugin',

    "names" => [
      "Друг",
      "Товарищ",
      "Заказчик",
      "Приятель",
      "Уважаемый начальник",
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
      "Самолет, в котором я летел, приземлился на запаснике в Новгороде",
      "Я ехал в поезде, и кто-то сорвал стоп-кран, я резко упал и ударился головой, сейчас в больнице",
      "Я поймал попутку, её остановила ГИБДД, и нашли крупную партию наркотиков. Сейчас я под следствием",
      "Я вышел из дома, а дверь захлопнулась, я попытался залезть через балкон, но упал. Сейчас я в травмпункте, иду на поправку",
      "Я шел по парку, и на меня напал бомж, он украл у меня кошелек и ключи от дома. Хорошо, хоть что не изнасиловал",
      "У меня развалилась кровать во время сна, я повредил позвоночник. Сейчас иду на поправку",
      "Я потерял паспорт",
      "Я на похоронах был, последний дедушка умер",
      "У меня рак нашли, я по больницам ездил",
      "У меня рак печени, к сожалению, серьезно, я сейчас химиотерапию прохожу",
      "Я потерял всё, что было в портмоне",
      "Меня избили цыгане",
      "Я просто в глуши был",
      "Я просто был не в городе",
      "Меня отправили по делам",
      "Твой банк отклонил перевод",
      "Мой счет заблокировали",
      "Я потерял ноутбук",
      "У меня сломался компьютер",
      "У меня полетел Windows",
      "Меня машина сбила",
      "Я немного не успеваю",
      "Я сейчас работаю по фрилансу",
      "Скоро стартап окупится",
      "Бабушка скоро пенсию получит",
      "Деньги вернул твой банк, пишет отказ, проверь номер карты",
      "Платеж на обработке",
      "Платеж отклонен, буду ругаться с банком",
      "Меня в армию забрали",
      "У меня кошка рожала",
      "У меня дочь родила",
      "У меня молоко убежало",
      "У меня квартира сгорела",
      "Я недооценил задачу",
      "Я недооценил масштаб задачи",
      "Я столкнулся с непредвиденными сложностями",
      "Мою собаку внезапно кастрировали и я должен о ней позаботиться",
    ],

    "action" => [
      "Я сделаю всё",
      "Вышлю часть",
      "Постараюсь",
      "Доберусь и всё сделаю",
      "Смогу сделать всё",
      "Я закончу",
      "Я доделаю",
      "Я исправлю",
      "Согласую всё",
      "Объясню всё подробнее",
      "Смогу отослать",
      "Смогу решить этот вопрос",
      "Смогу доделать",
      "Смогу закончить",
      "Сделаю перевод",
      "Переведу",
      "Приеду",
      "Я лично встречусь с тобой",
      "Я разберусь с этим",
      "Я разгребу это",
      "Решу всё",
      "Отправлю",
      "Скину",
      "Доеду до дома",
      "Приеду домой",
      "Закрою этот вопрос",
      "Попробую",
      "Давай встретимся",
      "Давай наличкой отдам",
    ],

    "date" => [
      "сейчас",
      "завтра",
      "завтра вечером",
      "завтра днем",
      "завтра утром",
      "как можно быстрее",
      "как можно скорее",
      "наконец-то",
      "чуть позже",
      "позже",
      "в течение 2 суток",
      "в конце недели",
      "в конце месяца",
      "в конце дня",
      "до конца следующей недели",
      "до завтра",
      "послезавтра",
      "ближе к вечеру",
      "ближе к утру",
      "с утра",
      "в крайнем случае завтра",
      "на неделе",
      "через пару дней",
      "скоро",
      "сразу",
      "сейчас, в течение 3-4 дней",
    ],

    "general" => [
      "Хочу закрыть вопрос поскорее",
      "Сам уже устал ждать",
      "Я бы с радостью уже все сделал",
      "Сам в шоке, что так всё получилось",
      "Сам в шоке, что так всё затягивается",
      "Сам не ожидал таких событий",
      "Надо поскорее решить этот вопрос",
      "Надо уже закрыть этот вопрос",
      "Надо уже решить эту проблему",
      "Я, конечно, очень извиняюсь, что так вышло",
    ],

  ];

  public function onPublicText( Update $update )
  {

    $text = $update->message()->text();
    if ($text->trigger() !== 'excuse') return;

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
    return "$hello. $fail. $action $date. $general.";
  }

  public function randomPhrase( array $items, array $replacements = [])
  {
    $random = mt_rand(0, count($items)-1);
    return strtr( $items[$random], $replacements);
  }

}