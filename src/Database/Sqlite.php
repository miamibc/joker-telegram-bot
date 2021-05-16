<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Database;

use RedBeanPHP\R;

trait Sqlite
{

  private $sqlite_cache;

  public function getCustom()
  {
    // is already loaded, return it
    if (!is_null($this->sqlite_cache))
      return $this->sqlite_cache;

    // try to read from database
    // table name is last word in class signature
    $class = explode('\\', get_class( $this ) );
    $class = strtolower(end($class));

    // try to find by uuid
    if (!$item = R::findOne( $class, 'uuid =?', [ $this->id() ]))
    {
      // if not found, create
      $item = R::dispense( $class );
      $item->uuid = $this->id();
    }

    // store in cache and return
    return $this->sqlite_cache = $item;
  }

  public function saveCustom()
  {
    if (!is_null($this->sqlite_cache))
      R::store($this->sqlite_cache);

    return $this;
  }

  public function cleanCustom()
  {
    if (!is_null($this->sqlite_cache))
      R::trash($this->sqlite_cache);

    return $this;
  }

}