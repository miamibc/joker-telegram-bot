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
  new Joker\TempPlugin( ['api_key' => getenv( 'OPENWEATHER_API_KEY' ), 'default' => 'Tallinn'] ),
  new Joker\SpotifyPlugin( ['client_id' => getenv( 'SPOTIFY_CLIENT_ID' ), 'secret'=>getenv( 'SPOTIFY_SECRET' )] ),
  new Joker\LurkPlugin(),
  new Joker\PastaPlugin(),
  new Joker\BashPlugin(),
  new Joker\RandomPlugin(),
  new Joker\CowsayPlugin(),
  new Joker\HelloPlugin(),
  new Joker\StickerPlugin(),
  new Joker\QuotePlugin( ['dir'=>'jokes'] ),
]);

do { $bot->loop(); } while(true);