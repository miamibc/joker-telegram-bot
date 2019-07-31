<?php
/**
 * Joker the Telegram bot
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'vendor/autoload.php';

$dotenv = \Dotenv\Dotenv::create(dirname(__FILE__));
$dotenv->load();

$channels = explode(",", getenv("TELEGRAM_CHANNELS"));
$token    = getenv('TELEGRAM_TOKEN') ;

$redis = new Predis\Client();

$last_update = $redis->get('joker_last_update')*1;

$bot = new \TelegramBot\Api\Client( $token );

/** @var $bot \TelegramBot\Api\BotApi */

$update = false;
foreach ( $bot->getUpdates( $last_update ) as $update)
{

  echo "\n" . $update->toJson();

  $message = $update->getMessage();
  if (!$message) $message = $update->getEditedMessage();
  if (!$message) $message = $update->getChannelPost();
  if (!$message) $message = $update->getEditedChannelPost();

  $channel = $message->getChat()->getTitle();

  if (!in_array( $channel, $channels )) { continue; }

  $author_id  = $message->getFrom()->getId();
  $channel_id = $message->getChat()->getId();
  $counter    = $redis->incr( "joker_channel_{$channel_id}_counter" );

  echo " Counter $counter";

  if ($message->getSticker() && $counter < 5)
  {

    $author = trim($message->getFrom()->getFirstName().' '.
                   $message->getFrom()->getLastName());
    $jokes = [
      'Это бан :p',
      'Печалька :DDD',
      'Вытрись, ты умер!',
      'Хорошая попытка, чувак :p',
      'Ничего личного...',
      'Выкуси :-E',
      'Ну и что? Вот и всё!',
      '1:0 ты проиграл :p',
      'Шах и мат',
    ];
    $joke = $jokes[array_rand($jokes)];
    // $answer  = "Sticker flood detected $author лишился стикера $counter-й раз. $joke";
    // $bot->sendMessage( $channel, $answer );

    try
    {
      $bot->deleteMessage(
        $message->getChat()->getId(),
        $message->getMessageId()
      );
      echo " Deleted";
    }
    catch (\TelegramBot\Api\HttpException $exception)
    {
      echo " $exception";
    }

    $redis->set( "joker_channel_{$channel_id}_counter", 0 );

  }
}

if ($update)
{
  $redis->set('joker_last_update', $update->getUpdateId()+1);
}