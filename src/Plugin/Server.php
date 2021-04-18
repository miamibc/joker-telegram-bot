<?php
/**
 * Server plugin for Joker
 *
 * Allows communication with bot by HTTP protocol
 * Start bot with this plugin attached, and run command:
 *
 * <pre>curl http://127.0.0.1:5566/sendMessage --data '{"chat_id":"-343502518", "text":"Testing Server Plugin"}'</pre>
 * or
 * <pre>echo '{"chat_id":"-343502518", "text":"Testing Server Plugin"}' | telnet 127.0.0.1 5566</pre>
 *
 * You will receive message from Joker in your chat.
 *
 * Options:
 * - `address` (string, optional, default 127.0.0.1)  - address of interface to listen
 * - `port` (integer, optional, default 5566) - port number
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Event;
use Joker\Plugin;

class Server extends Plugin
{

  private $sock;

  public function __construct($options = [])
  {
    parent::__construct($options);

    $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
    socket_bind($sock,
                $this->getOption('address', '127.0.0.1'),
                $this->getOption('port', 5566));
    socket_listen($sock, 5);
    socket_set_nonblock($sock);
    $this->sock = $sock;
  }

  public function onEmpty( Event $event)
  {

    $conn = socket_accept($this->sock);
    if ($conn === false) return;

    socket_set_nonblock($conn);
    socket_recv( $conn, $input, 2048, MSG_DONTWAIT);

    $input = explode("\r\n\r\n", $input);

    switch(count($input))
    {
      case 1:
        $method  = 'sendMessage';
        $request = json_decode( $input[0], true );
        break;
      case 2:
        $method  = preg_match('@^POST /(\w+)@', $input[0], $matches) ? $matches[1] : 'sendMessage';
        $request = json_decode( $input[1], true );
        break;
      default:
        socket_close($conn);
        return;
    }

    $response = $event->getBot()->customRequest( $method , $request);
    $response = json_encode($response);
    $raw = "HTTP/1.1 200 OK\nContent-Length: " . strlen($response) . "\r\n\r\n" . $response ;
    socket_write($conn, $raw, strlen($raw) );
    socket_close($conn);
  }

  public function __destruct()
  {
    socket_close( $this->sock );
  }

}