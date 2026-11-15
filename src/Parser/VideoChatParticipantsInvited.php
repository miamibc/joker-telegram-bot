<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * This object represents a service message about new members invited to a video chat.
 * @see https://core.telegram.org/bots/api#videochatparticipantsinvited
 *
 * @method User[] users() New members that were invited to the video chat
 */
class VideoChatParticipantsInvited extends Base
{

  protected $wrapper = [
    'users' => User::class,
  ];

}
