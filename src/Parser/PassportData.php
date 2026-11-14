<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes Telegram Passport data shared with the bot by the user.
 * @see https://core.telegram.org/bots/api#passportdata
 *
 * @method EncryptedPassportElement[] data() Array with information about documents and other Telegram Passport elements that was shared with the bot
 * @method EncryptedCredentials credentials() Encrypted credentials required to decrypt the data
 */
class PassportData extends Base
{

  protected $wrapper = [
    'data' => EncryptedPassportElement::class,
    'credentials' => EncryptedCredentials::class,
  ];

}
