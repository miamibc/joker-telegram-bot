<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

class Joker extends \TelegramBot\Api\Client
{

  public function __construct($token,$trackerToken = null)
  {
    parent::__construct($token,$trackerToken);

  }

}