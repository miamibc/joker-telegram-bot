<?php
/**
 * Joker the Telegram bot
 *
 * Born in 2001'th this bot was entertaiment chatbot made in miRCscript,
 * joking on channel #blackcrystal in Quakenet. Since that year many things
 * has been changed. Here's third rewrite of Joker on PHP and Telegram API.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(dirname(__FILE__));
$dotenv->load();

$token    = getenv('TELEGRAM_TOKEN');
$channels = explode(",", getenv("TELEGRAM_CHANNELS"));

$bot = new Joker\Bot( $token );
$bot->plug([
  new Joker\HelloPlugin(),
  new Joker\LogPlugin(['file'=>'log/log.json']),
  // new Joker\QuotePlugin(),
  $stickerplugin = new Joker\StickerPlugin(),
]);

$stickerplugin->scanLog('log/log.json');

do { $bot->loop(); } while(true);