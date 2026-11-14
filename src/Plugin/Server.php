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

use Joker\Parser\Update;

class Server extends Base
{

  protected $options = [
    'description' => 'Server plugin',
    'risk' => 'LOW. Nothing stored by plugin',
  ];

  /** @var \Socket|null listening socket, null if not listening */
  private $sock = null;

  public function init()
  {
    $address = $this->getOption('address', '127.0.0.1');
    $port    = $this->getOption('port', 5566);

    if (!$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))
    {
      $this->bot()->console("Server plugin: can't create socket: " . socket_strerror(socket_last_error()));
      return;
    }

    // allow to listen the port again right after restart, when old connections are still in TIME_WAIT
    socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);

    if (!@socket_bind($sock, $address, $port) || !@socket_listen($sock, 5))
    {
      $this->bot()->console("Server plugin: can't listen $address:$port: " . socket_strerror(socket_last_error($sock)));
      socket_close($sock);
      return;
    }

    socket_set_nonblock($sock);
    $this->sock = $sock;
  }

  public function onEmpty( Update $update)
  {
    if (!$this->sock) return;

    $conn = @socket_accept($this->sock);
    if ($conn === false) return;

    socket_set_nonblock($conn);
    if (!socket_recv( $conn, $input, 2048, MSG_DONTWAIT))
    {
      socket_close($conn);
      return;
    }

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

    $response = $update->bot()->customRequest( $method , $request);
    $response = json_encode($response);
    $raw = "HTTP/1.1 200 OK\nContent-Length: " . strlen($response) . "\r\n\r\n" . $response ;
    socket_write($conn, $raw, strlen($raw) );
    socket_close($conn);
  }

  public function __destruct()
  {
    if ($this->sock) socket_close( $this->sock );
    $this->sock = null;
  }

}