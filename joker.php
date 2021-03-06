<?php
/**
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'autoload.php';

$bot = new Joker\Bot( getenv( 'TELEGRAM_TOKEN' ) );
$bot->plug([

  // *** something, that must be executed everytime, must stay at top ***

  new Joker\Plugin\Log( ['file' =>'data/log/log.json'] ),
  new Joker\Plugin\Activity( ['sync_time' => 60] ),
  new Joker\Plugin\Kicker(),
  new Joker\Plugin\Forwarder( [
    ['from' => -343502518, 'text' => ['*покуп*'], 'to' => -343502518, ],
    ['from' => -343502518, 'text' => ['*прода*', '*сдаё*'], 'to' => -343502518, 'forward' => false ],
  ]),
  new Joker\Plugin\UrlCollector(['file' => 'data/urls.txt']),

  // *** insert your plugins here, order is important ***

  new Joker\Plugin\Server( ['host' => '127.0.0.1', 'port' => 5566] ),
  new Joker\Plugin\Temp( ['api_key' => getenv( 'OPENWEATHER_API_KEY' ),'default' => 'Tallinn'] ),
  new Joker\Plugin\Spotify( ['client_id' => getenv( 'SPOTIFY_CLIENT_ID' ),'secret' =>getenv( 'SPOTIFY_SECRET' )] ),
  new Joker\Plugin\Lurk(),
  new Joker\Plugin\Bash(),
  new Joker\Plugin\Cowsay( ['bg_color' =>'#222222','text_color' =>'#dadada']),
  new Joker\Plugin\Hello(),
  new Joker\Plugin\Sticker(),
  new Joker\Plugin\Carma(['clean_time' => false, 'power_time' => 600,'start_carma' => 10]),
  new Joker\Plugin\Quote( ['dir' =>'data/jokes'] ),
  new Joker\Plugin\Corona( ['file' => 'data/corona/today.csv', 'update_hours'=>3]),
  new Joker\Plugin\Currency(),
  new Joker\Plugin\Callback(['trigger'=>'callbacktest', 'callback' => function(Joker\Event $event){
    $event->answerMessage('test ok');
    return false;
  }]),
  new Joker\Plugin\Twitch(['client_id'=>getenv('TWITCH_CLIENT_ID'), 'secret'=>getenv('TWITCH_CLIENT_SECRET')]),
  new Joker\Plugin\Meme(),
  new Joker\Plugin\Excuse(),
  new Joker\Plugin\Whynot(),

  // *** somethingg wide, without triggers, must stay in the end ***

  new Joker\Plugin\Pasta( ['minimum_time' => 60 * 60] ),
  new Joker\Plugin\Beer( ['minimum_time'=>15*60] ),

]);

do { $bot->loop(); } while(true);