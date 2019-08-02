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

$bot = new \TelegramBot\Api\Client( $token );

/** @var $bot \TelegramBot\Api\BotApi */

// get id of last update until now and collect all stickers

$stickers = [];

$log = fopen(dirname(__FILE__) . '/log/log.json' , 'r');
while (($line = fgets( $log )) !== false)
{
  $line = json_decode( $line , true );
  $id = $line['update_id'];
  if (isset($line['message']['sticker']['file_id']))
  {
    $stickers[] = $line['message']['sticker']['file_id'];
  }
}
fclose($log);

$stickers = array_unique($stickers);

echo "\nCollected ".count($stickers)." stickers, last id $id\n";
echo "Starting up "; sleep(1); echo "."; sleep(1); echo "."; sleep(1); echo "."; sleep(1);

$log = fopen(dirname(__FILE__) . '/log/log.json' , 'a');

do
{

  foreach ($bot->getUpdates( $id+1 ) as $update)
  {

    $json = $update->toJson();
    fputs( $log, $json."\n" );
    echo $update->toJson()."\n";

    $id = $update->getUpdateId();

    $message = $update->getMessage();
    if (!$message) $message = $update->getEditedMessage();
    if (!$message) $message = $update->getChannelPost();
    if (!$message) $message = $update->getEditedChannelPost();

    $channel = $message->getChat()->getTitle();
    if (!in_array($channel,$channels)) { continue; }

    $author_id = $message->getFrom()->getId();
    $chat_id = $message->getChat()->getId();

    if ($message->getSticker())
    {
      $stickers[] = $message->getSticker()->getFileId();
      $stickers = array_unique( $stickers );
      shuffle( $stickers );
      $files = array_slice( $stickers, 0, rand(0, 3));
      if (count($files))
      {
        foreach ( $files as $file_id)
          $bot->sendSticker($chat_id,$file_id,null,null,true);

        $author = trim($message->getFrom()->getFirstName().' '.$message->getFrom()->getLastName());
        $answers = [
          "I like this shit, $author. Let's do it again :p",
          "It was incredible fun, $author please repeat :D",
          "Lololololo stickers is my love, $author :*",
          "Wow nice, $author. Mo stickers, morrrr",
        ];
        $bot->sendMessage($chat_id,$answers[ array_rand( $answers )]);
      }
    }

  }

  sleep(3);

} while (true);