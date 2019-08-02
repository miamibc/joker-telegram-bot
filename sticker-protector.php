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

$dotenv = \Dotenv\Dotenv::create(dirname(__FILE__));
$dotenv->load();

$token    = getenv('TELEGRAM_TOKEN');
$channels = explode(",", getenv("TELEGRAM_CHANNELS"));

$redis = new Predis\Client();

$last_update = $redis->get('joker_last_update')*1;

$bot = new \TelegramBot\Api\Client( $token );

/** @var $bot \TelegramBot\Api\BotApi */

do
{

  $update = false;
  foreach ($bot->getUpdates($last_update) as $update)
  {

    $message = $update->getMessage();
    if (!$message)
      $message = $update->getEditedMessage();
    if (!$message)
      $message = $update->getChannelPost();
    if (!$message)
      $message = $update->getEditedChannelPost();

    $channel = $message->getChat()->getTitle();
    if (!in_array($channel,$channels))
    {
      continue;
    }

    echo "\n".$update->toJson();

    $author_id = $message->getFrom()->getId();
    $channel_id = $message->getChat()->getId();

    $counter = $redis->incr($key = "joker_channel_{$channel_id}_counter");

    if ($message->getSticker())
    {
      if ($counter < 0)
      {
        echo " sticker deleted";
        /*
        $author = trim($message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName());
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
        $answer  = "Sticker flood detected $author лишился стикера $counter-й раз. $joke";
        $bot->sendMessage( $channel, $answer );
        */

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

      }

      $redis->set($key, $counter = -5);

    }

    // some stats here
    echo " counter $counter";

  }

  if ($update)
  {
    $last_update = $update->getUpdateId() + 1;
    $redis->set('joker_last_update', $last_update);
  }

  sleep(3);


} while (true);