<?php
/**
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

require 'autoload.php';

$stdin = fopen('php://stdin', 'r');
$stdout = fopen('php://stdout', 'w');
while ( ($data = fgets( $stdin )) !== false)
{
  $line = json_decode( $data, true );
  $text = isset( $line['message']['text']) ? $line['message']['text'] : $data;
  $author = isset( $line['message']['from']['username']) ? @$line['message']['from']['username'] : @$line['message']['from']['first_name'];
  $date   = isset( $line['message']['date']) ? date( 'Y-m-d H:i:s', $line['message']['date'] ): 'no date';
  fputs( $stdout, "[$date] <$author> $text\n");
}
fclose( $stdin );
fclose( $stdout );