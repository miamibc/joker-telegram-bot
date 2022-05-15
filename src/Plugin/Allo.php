<?php

namespace Joker\Plugin;

use Joker\Parser\Update;

class Allo extends Base
{

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
  }

}