<?php
/**
 * Joker Hello Plugin
 *
 * Say /start or hello to the bot in private chat
 * Send sticker, it answers with random sticker from same sticker pack
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use Joker\Plugin;

class Hello extends Base
{

  public function onPrivateText( Update $update )
  {

    if (!preg_match('@^(/start|hello|hi|yo)\b@ui', $update->message()->text())) return;

    $name = $update->message()->from()->name();

    $message = <<<EOF
Hello, $name. I'm Joker, the Telegram Bot.

Born in 2001, I was entertainment chatbot written in miRCscript. Now I'm a bit new: I run PHP on fast virtual server to connect modern geeky Telegram network and joke my random funs.

Read more: https://github.com/miamibc/joker-telegram-bot
EOF;

    $update->answerMessage( $message );
    return false;
  }

}