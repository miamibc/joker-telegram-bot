<?php
/**
 * Telegram Bot API parser for Joker
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * Describes the current status of a webhook.
 * @see https://core.telegram.org/bots/api#webhookinfo
 *
 * @method string url() Webhook URL, may be empty if webhook is not set up
 * @method bool has_custom_certificate() True, if a custom certificate was provided for webhook certificate checks
 * @method integer pending_update_count() Number of updates awaiting delivery
 * @method string ip_address() Optional. Currently used webhook IP address
 * @method integer last_error_date() Optional. Unix time for the most recent error that happened when trying to deliver an update via webhook
 * @method string last_error_message() Optional. Error message in human-readable format for the most recent error that happened when trying to deliver an update via webhook
 * @method integer last_synchronization_error_date() Optional. Unix time of the most recent error that happened when trying to synchronize available updates with Telegram datacenters
 * @method integer max_connections() Optional. The maximum allowed number of simultaneous HTTPS connections to the webhook for update delivery
 * @method string[] allowed_updates() Optional. A list of update types the bot is subscribed to. Defaults to all update types except chat_member, message_reaction, and message_reaction_count.
 */
class WebhookInfo extends Base
{

  protected $wrapper = [
  ];

}
