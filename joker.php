<?php
/**
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
  new Joker\StickerPlugin(),
  new Joker\LogPlugin(['file'=>'log/log.json']),
  new Joker\QuotePlugin(['dir'=>'jokes']),
  $moderator = new Joker\ModeratorPlugin(),
]);

$moderator->scanLog('log/log.json');

do { $bot->loop(); } while(true);