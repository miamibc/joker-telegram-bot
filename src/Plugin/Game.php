<?php
/**
 * Game Plugin for Joker
 *
 * Ask bot for a game with name, for example, chpocker
 *   !chpocker
 *
 * Bot will send you a game
 *
 * Options:
 * - `trigger` (string, required) - short name of a game, will be used to request game by typing !trigger in private or public chat
 * - `url`     (string, required) - URL of a game
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Game extends Base
{

  /**
   * Listen to public and private chat text message
   * with trigger !chpocker, and answers with our game
   *
   * @param Update $update
   * @return false|void
   */
  public function onMessageText( Update $update )
  {
    // if no trigger or url defined, do not process
    if (!$trigger = $this->getOption('trigger')) return;
    if (!$url = $this->getOption('url')) return;

    if ($update->message()->text()->trigger() !== $trigger) return;
    $update->bot()->customRequest('sendGame', [
      'chat_id' => $update->message()->chat()->id(),
      'game_short_name' => $trigger,
    ]);
    return false;
  }

  /**
   * Listen to CallbackQuery with game name we serve, and answer with URL
   * @param Update $update
   * @return false|void
   */
  public function onCallback( Update $update )
  {
    // if no trigger or url defined, do not process
    if (!$trigger = $this->getOption('trigger')) return;
    if (!$url = $this->getOption('url')) return;

    if ($update->callback_query()->game_short_name() !== $trigger) return;

    $update->callback_query()->answer([
      'url' => $url,
    ]);

    return false;
  }

}