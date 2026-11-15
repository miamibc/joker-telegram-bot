<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Bot;

use Joker\Bot;

/**
 * Bot without Telegram: remembers requests, answers with prepared results,
 * gives prepared updates and doesn't sleep between loop iterations
 */
class FakeBot extends Bot
{

  public $requests = [];   // [method, data, multipart]
  public $responses = [];  // method => result, true if not set
  public $updates = [];    // updates returned by next getUpdates call

  public function __construct()
  {
    parent::__construct(false);
  }

  protected function _request( $method, $data = [] )
  {
    return $this->fake( $method, $data, false );
  }

  protected function _requestMultipart( $method, $data = [] )
  {
    return $this->fake( $method, $data, true );
  }

  private function fake( $method, $data, $multipart )
  {
    $this->requests[] = [$method, $data, $multipart];
    if ($method === 'getUpdates')
    {
      $updates = $this->updates;
      $this->updates = [];
      return $updates;
    }
    return array_key_exists($method, $this->responses) ? $this->responses[$method] : true;
  }

  protected function pause()
  {
  }

  /**
   * Last request
   * @return array [method, data, multipart]
   */
  public function last()
  {
    return end($this->requests);
  }

}
