<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a phone contact.
 * @see https://core.telegram.org/bots/api#contact
 *
 * @method string phone_number() Contact's phone number
 * @method string first_name() Contact's first name
 * @method string last_name() Optional. Contact's last name
 * @method integer user_id() Optional. Contact's user identifier in Telegram. This number may have more than 32 significant bits and some programming languages may have difficulty/silent defects in interpreting it. But it has at most 52 significant bits, so a 64-bit integer or double-precision float type are safe for storing this identifier.
 * @method string vcard() Optional. Additional data about the contact in the form of a vCard
 */
class Contact extends Base
{

  protected $wrapper = [
  ];


}