<?php
/**
 * Ignore plugin for Joker
 *
 * Adds ability to be ignored in processing all incoming events.
 * Additionally, ignored all messages sent via bot.
 *
 * To  be ignored say:
 * !ignore
 *
 * To be unignored, say:
 * !unignore
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Ignore extends Base
{

  protected $options = [
    'description' => 'Ignore plugin',
    'risk' => 'LOW. Ignore status stored in local database',
  ];

  public function onViabot( Update $update )
  {
    return false;
  }

  public function onText( Update $update )
  {
    $trigger = $update->message()->text()->trigger();
    if (!in_array($trigger, ['ignore','unignore'])) return;

    $user   = $update->message()->from();
    $custom = $user->getCustom();
    $custom->ignore = ($trigger === 'ignore');
    $user->saveCustom();
    $update->answerMessage("$user is {$trigger}d");
    return false;
  }

  public function onMessage( Update $update )
  {
    if ($update->message()->from()->getCustom()->ignore) return false;
  }

}