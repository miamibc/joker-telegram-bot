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
echo "Starting up "; sleep(1); echo "."; sleep(1); echo "."; sleep(1); echo "."; sleep(1);echo "\n";

$log = fopen(dirname(__FILE__) . '/log/log.json' , 'a');

do
{

  try {
    $updates = $bot->getUpdates($id + 1);
  }
  catch (TelegramBot\Api\HttpException $exception) {
    $updates = [];
  }

  foreach ($updates as $update)
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


    if($text = $message->getText())
    {
      $params = preg_split('@\s+@', $text);
      $command = trim( strtolower( preg_replace("@[^!\w]@", "", $params[0]) ));
      if (strlen($command) && $command[0] === '!' && file_exists($file  = "jokes/$command.txt"))
      {
        $file = file($file);
        if (!isset($params[1]))
        {
          // random
          $rand = mt_rand(0, $count = count($file)-1);
        }
        elseif (isset($params[1]) && $params[1][0] === '#'){
          // number
          $rand = substr( $params[1], 1 )*1-1;
        }
        else {
          // exact match
          $found = array_filter( $file, function ($value){
            global  $params;
            return preg_match('#\b'.preg_quote( $params[1] ).'\b#iu', $value);
          });
          // relaxed match
          if (!count($found))
          {
            $found = array_filter( $file, function ($value){
              global  $params;
              return preg_match('#'.preg_quote( $params[1] ).'#iu', $value);
            });
          }
          $rand = array_rand( $found );
          unset($found);
        }
        $joke_id = $rand+1;
        if (isset($file[$rand]))
          $bot->sendMessage($chat_id, "$command #{$joke_id}: {$file[$rand]}");
        else
          $bot->sendMessage($chat_id, "$command: Joke not found :-(");
        unset($file);
      }
    }
    elseif ($message->getSticker())
    {
      $stickers[] = $message->getSticker()->getFileId();
      $stickers = array_unique( $stickers );
      shuffle( $stickers );
      $files = array_slice( $stickers, 0, mt_rand(0, 3));
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