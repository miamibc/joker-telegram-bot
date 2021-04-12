<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

class Update
{

  private $data = [];

  public function __construct( $data )
  {
    $this->data = $data;
  }

  public function getId()
  {
    return $this->getUpdateId();
  }

  public function getUpdateId()
  {
    return $this->data['update_id'];
  }

  public function getMessage()
  {
    return new Message( $this->data['message']);
  }

}