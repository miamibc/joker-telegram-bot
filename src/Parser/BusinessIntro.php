<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Contains information about the start page settings of a Telegram Business account.
 * @see https://core.telegram.org/bots/api#businessintro
 *
 * @method string title() Optional. Title text of the business intro
 * @method string message() Optional. Message text of the business intro
 * @method Sticker sticker() Optional. Sticker of the business intro
 */
class BusinessIntro extends Base
{

  protected $wrapper = [
    'sticker' => Sticker::class,
  ];

}
