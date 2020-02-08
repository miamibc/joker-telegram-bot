<?php
/**
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'vendor/autoload.php';

$dotenv = Dotenv\Dotenv::create(dirname(__FILE__));
$dotenv->load();

$bot = new Joker\Bot( getenv( 'TELEGRAM_TOKEN' ) );
$bot->plug([
  new Joker\LogPlugin( ['file'=>'log/log.json'] ),
  new Joker\TempPlugin( ['api_key' => getenv( 'OPENWEATHER_API_KEY' )] ),
  new Joker\SpotifyPlugin( ['token' => getenv( 'SPOTIFY_TOKEN' )] ),
  new Joker\RandomPlugin(),
  new Joker\CowsayPlugin(),
  new Joker\HelloPlugin(),
  new Joker\StickerPlugin(),
  new Joker\QuotePlugin( ['dir'=>'jokes'] ),
]);

do { $bot->loop(); } while(true);