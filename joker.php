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
  new Joker\Plugin\Kicker([
    'seconds_with_emoji' => 0,
    'seconds_without_emoji' => 600,
    'greeting_with_emoji' => 'Привет, %name%. Похоже ты бот, так что не будем тянуть...',
    'greeting_without_emoji' => 'Привет, %name%. Добро пожаловать на наш Беломор-канал. Чтобы не быть как бот, просим написать сюда "привет" или что-то в этом духе, как можно быстрее ;) Иначе тебе положен бан :p',
    'greeting_is_bot' => 'Пока, %name%!',
    'greeting_not_bot' => 'Спасибо большое, будем знакомы ;)',
  ]),
  new Joker\Plugin\Forwarder( [
    ['from' => -343502518, 'text' => ['*покуп*'], 'to' => -343502518, ],
    ['from' => -343502518, 'text' => ['*прода*', '*сдаё*'], 'to' => -343502518, 'forward' => false ],
  ]),
  new Joker\Plugin\Ignore(),
  new Joker\Plugin\UrlCollector(['file' => 'data/urls.txt']),
  // new Joker\Plugin\Viabot(), // TODO: not necessary anymore, will be removed soon

  // *** insert your plugins here, order is important ***

  new Joker\Plugin\Server( ['host' => '127.0.0.1', 'port' => 5566] ),
  new Joker\Plugin\Temp( ['api_key' => getenv( 'OPENWEATHER_API_KEY' ),'default' => 'Tallinn'] ),
  new Joker\Plugin\Advice([
    'random_time'   => 60*60, // time condition (one advice per hour)
    'random_ticks'  => 5,     // tick condition (5 messages in last minute)
    'random_chance' => .33,   // random chance (33%)
    'random_delay'  => 5,     // random advice delay
  ]),
  new Joker\Plugin\Flip(),
  new Joker\Plugin\Vkmusic(),
  new Joker\Plugin\Ytmusic( ['api_key' => getenv('GOOGLE_API_KEY')]),
  new Joker\Plugin\Spotify( ['client_id' => getenv( 'SPOTIFY_CLIENT_ID' ),'secret' =>getenv( 'SPOTIFY_SECRET' )] ),
  new Joker\Plugin\Lurk(),
  new Joker\Plugin\Anek(),
  new Joker\Plugin\Bash(),
  new Joker\Plugin\Cowsay( ['bg_color' =>'#222222','text_color' =>'#dadada']),
  new Joker\Plugin\Hello(),
  new Joker\Plugin\Sticker(),
  new Joker\Plugin\StickerFun(['range' => 300]),
  new Joker\Plugin\Carma(['clean_time' => false, 'power_time' => 600,'start_carma' => 10]),
  new Joker\Plugin\Corona( ['file' => 'data/corona/today.csv', 'update_hours'=>3]),
  new Joker\Plugin\Currency(),
  new Joker\Plugin\Callback(['callbacktest' => function(Joker\Parser\Update $update){
    $update->answerMessage('test ok');
    return false;
  }]),
  new Joker\Plugin\Twitch(['client_id'=>getenv('TWITCH_CLIENT_ID'), 'secret'=>getenv('TWITCH_CLIENT_SECRET')]),
  new Joker\Plugin\Meme(),
  new Joker\Plugin\Excuse(),
  new Joker\Plugin\Whynot(),
  new Joker\Plugin\Uptime(),
  new Joker\Plugin\Game( [ 'trigger' => 'chpocker', 'url' => 'https://blackcrystal.dev/chpocker/']),
  new Joker\Plugin\Stats( ['file' =>'data/log/log.json'] ),
  new Joker\Plugin\Mastodon(),
  new Joker\Plugin\OpenAi([
    'api_key' => getenv('OPENAI_API_KEY'),
    'context_length' => 1000,
    'premium_only' => false,
    'bio' => implode("\n", [
      'Your name is Joker or Джокер. You are russian-speaking friend, that answers with sarcastic responses and funny jokes.',
      'Your author is Sergei Miami and BlackCrystal team.',
      'You live in Tallinn, today is ' . date(DATE_RFC1123),
    ]),
    'model' => 'chatgpt-4o-latest', // o1-mini gpt-4o
    'temperature' =>  0.5,
    'max_tokens' =>  500,
    'top_p' => 0.3,
    'frequency_penalty' => 0.5,
    'presence_penalty' => 0.0,
  ]),
  new Joker\Plugin\Privacy(),

  // *** somethingg wide, without triggers, must stay in the end ***

  new Joker\Plugin\Quote(),
  new Joker\Plugin\QuoteInline( ['trigger'=> 'tg', 'limit' => 5, 'length' => 80] ),
  new Joker\Plugin\QuoteAdmin(),
  new Joker\Plugin\Pasta( ['minimum_time' => 60 * 60] ),
  new Joker\Plugin\Beer( ['minimum_time'=>15*60] ),

]);

do { $bot->loop(); } while(true);