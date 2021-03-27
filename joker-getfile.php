<?php
/**
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'autoload.php';

$token = getenv( 'TELEGRAM_TOKEN' );
$file_id = $argv[1];

$bot = new Joker\Bot( $token );

$result = $bot->customRequest('getFile', ['file_id'=>$file_id]);

if (!isset($result['file_path']))
  throw new Exception("file_path not found");

$data = file_get_contents( "https://api.telegram.org/file/bot$token/$result[file_path]" );
if (!$data)
  throw new Exception("Empty file");

echo($data);