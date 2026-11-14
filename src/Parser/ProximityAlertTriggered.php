<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents the content of a service message, sent whenever a user in the chat triggers a proximity alert set by another user.
 * @see https://core.telegram.org/bots/api#proximityalerttriggered
 *
 * @method User traveler() User that triggered the alert
 * @method User watcher() User that set the alert
 * @method integer distance() The distance between the users
 */
class ProximityAlertTriggered extends Base
{

  protected $wrapper = [
    'traveler' => User::class,
    'watcher' => User::class,
  ];

}
