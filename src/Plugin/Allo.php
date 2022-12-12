<?php

namespace Joker\Plugin;

use Joker\Parser\Update;

class Allo extends Base
{

  private $last_guba = 0;

  public function onPublicText( Update $update)
  {

    if ($update->message()->text()->contains('алло') || $update->message()->text()->contains('алё') || $update->message()->text()->contains('ало') )
    {
      $answers = [
        "На проводе!",
        "Слушаю вас, говорите",
        "Алоэ?",
        "Алоха брад",
        "Алё, алё, ааааало",
        "Оллоло",
        "Смольный",
        "Ало, салют",
        "Приём...",
        'В табло!',
        "Да, да Алло товарищ",
        "Ало, чё как житёха, не обижает никто?",
        "Приём-приём",
        "Я вас слушаю",
      ];
      $update->replyMessage($answers[mt_rand(0, count($answers)-1)]);
      return false;
    }

    return;

    if ($update->message()->from()->username() === 'BC_D0b3rm4nN' && time() - $this->last_guba > 45*60)
    {
      $start = [
        "Ой чёрт",
        "Фак",
        "Да блин",
        "Сцк",
        "Да сколько можно",
        "Чтоб меня",
        "Хватит так шутить",
        "Спасити",
      ];
      $end = [
        "у меня губа треснула",
        "опять моя губа треснула",
        "снова губа треснула",
        "губа моя губа",
        "треснула губа",
        "снова треснула, ага",
        "опять трещит моя губа",
        "снова губа трещит",
        "трещи трещи моя губа",
        "сделайте же что-нибудь с моей губой",
      ];
      $update->replyMessage($start[mt_rand(0, count($start)-1)] . ', ' . $end[mt_rand(0, count($end)-1)] );
      $this->last_guba = time();
      return false;
    }

  }

}