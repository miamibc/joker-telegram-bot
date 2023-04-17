<?php
/**
 * Joker Quote Admin Plugin
 * Started in the sky, flight from Berlin to Tallinn 29 aug
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;
use RedBeanPHP\R;

class QuoteAdmin extends Base
{

  protected $options = [
    'description' => 'Quote Admin',
    'risk' => 'MEDIUM. Only jokes stored by plugin in a local database by admins',
  ];

  private $sessions = [];

  public function onPrivateText( Update $update )
  {
    // if logged in
    $logged_in = isset( $this->sessions[ $update->message()->from()->id() ]);
    $trigger = $update->message()->text()->trigger();

    // only one command available for not-logged-in-user
    if (!$logged_in && !in_array($trigger,['login'])) return;

    // check method exists
    if (!method_exists($this, $trigger))
    {
      $update->answerMessage("Unknown command. Type <code>help</code> for list of commands",['parse_mode' => 'HTML']);
      return false;
    }

    // call this method
    return call_user_func([$this, $trigger], $update);

  }

  /**
   * Starts the dialogue with bot in admin mode
   * @param Update $update
   */
  public function login( Update $update )
  {
    // get custom user info
    $custom = $update->message()->from()->getCustom();

    // if admin_triggers is empty, nothing to admin
    if (!$custom->admin_triggers)
    {
      $update->answerMessage('Sorry, you have no triggers to work with. Please ask admins to add this ability to you.');
      return false;
    }

    // create session
    $this->sessions[ $update->message()->from()->id() ] = true;

    // current trigger if frist trigger, if it's not set
    if (!$custom->admin_current_trigger)
    {
      $triggers = explode(' ', $custom->admin_triggers);
      $custom->admin_current_trigger = reset($triggers);
      $update->message()->from()->saveCustom();
    }

    $update->answerMessage( "Hi, <b>{$update->message()->from()->name()}</b> you are logged in as admin. Triggers you can work with: <b>{$custom->admin_triggers}</b>. Current trigger is {$custom->admin_current_trigger}. Tyoe <code>help</code> for available commands." , ['parse_mode' => 'HTML']);
    return false;
  }


  /**
   * Log out
   * @param Update $update
   */
  public function logout( Update $update )
  {
    unset( $this->sessions[ $update->message()->from()->id()]);
    $update->answerMessage( "Logged out, buj :p" , ['parse_mode' => 'HTML']);
    return false;
  }

  /**
   * Show help
   * @param Update $update
   */
  public function help( Update $update )
  {
    $message = <<<EOF
Commands:
<code>login</code> - to log in as admin
<code>cd [trigger]</code> - to change trigger
<code>ls [number]</code> - to list last [number] jokes in current trigger
<code>add [joke]</code> - to add joke to current trigger
<code>rm [number]</code> - to remove joke by number
<code>logout</code> - to log out from admin
EOF;
    $update->answerMessage( $message , ['parse_mode' => 'HTML']);
    return false;
  }

  /**
   * Change to trigger
   * @param Update $update
   *
   * @return false
   */
  public function cd( Update $update )
  {
    $trigger = $update->message()->text()->token(1);
    $custom  = $update->message()->from()->getCustom();
    $triggers = explode(" ", $custom->admin_triggers);
    if (!in_array($trigger, $triggers))
    {
      $update->answerMessage("Cannot change to this trigger. Triggers available: {$custom->admin_triggers}");
      return false;
    }
    $custom->admin_current_trigger = $trigger;
    $update->message()->from()->saveCustom();
    $update->answerMessage("Changed to $trigger");
    return false;
  }

  /**
   * List jokes
   * @param Update $update
   *
   * @return false
   */
  public function ls( Update $update )
  {
    $limit   = $update->message()->text()->token(1);
    if (!$limit) $limit = 5;
    if (!is_numeric($limit))
    {
      $update->answerMessage( "Parameter must be a number");
      return false;
    }

    $custom  = $update->message()->from()->getCustom();
    $trigger = $custom->admin_current_trigger;
    $result = [];
    foreach ( R::find('joke', " trigger = ? ORDER BY id DESC LIMIT ? ", [ $trigger, $limit ]) as $item)
    {
       $result[] = "#$item->id $item->joke";
    }
    if (empty($result))
    {
      $result[] = "Nothing found";
    }
    $update->answerMessage( implode("\n\n" , array_reverse( $result )));
    return false;
  }

  /**
   * Remove joke by ID
   * @param Update $update
   *
   * @return false
   */
  public function rm(Update $update)
  {
    $id   = $update->message()->text()->token(1);
    if (!is_numeric($id))
    {
      $update->answerMessage( "Parameter must be a number");
      return false;
    }

    $custom  = $update->message()->from()->getCustom();
    $trigger = $custom->admin_current_trigger;
    $item = R::findOne('joke', " trigger = ? AND id = ? ", [ $trigger, $id ]);
    if (!$item)
    {
      $update->answerMessage("Cannot find this quote in $trigger");
      return false;
    }
    $update->answerMessage("Deleted #$item->id $item->joke");
    R::trash($item);
    return false;
  }

  /**
   * Add a joke to current trigger
   * @param Update $update
   *
   * @return false
   * @throws \RedBeanPHP\RedException\SQL
   */
  public function add( Update $update )
  {
    $text    = $update->message()->text()->substring(4);

    if ( preg_match( $regexp = '#^(.*), \[([^]]+)\]\n(.*?)$#m', $text, $matches) )
    {
      // multi-line telegram-x format
      $text = preg_replace($regexp,'<\1> \3',$text); // make <name> text lines
      $text = preg_replace('#\n+#m','\n',$text);     // change newlines to special newline
      $text = '['.$matches[2].']\n'.trim($text);     // add date
    }
    elseif (preg_match_all('#^(.*):#m', $text, $matches, PREG_OFFSET_CAPTURE))
    {
      // multi-line telegram format
      $result = [];
      foreach ($matches[1] as $num => $match)
      {
        $start = strlen($match[0])+2+$match[1];  // calculate start of message by adding length of name to start offset
        $message = isset($matches[1][$num+1][1]) // if next match exists
          ? substr( $text, $start, $matches[1][$num+1][1] - $start) // get text from start to next offset
          : substr( $text, $start) // otherwise get all
        ;
        $message = preg_replace('#\n+#m','\n',trim( $message )); // replace newlines with special newline
        $result[] = "<$match[0]> $message";
      }
      $text = implode('\n', $result);
    }
    else
    {
      // old-school, IRC-joker format
      $text = trim($text);
      $text = preg_replace('#\n+#m','\n',$text); // replace newlines with special newlines
      $text = preg_replace('#\s+#m',' ',$text);  // replace long spaces to normal spaces
    }

    // change special newline \n back to normal
    $text = trim( strtr( $text , ['\n' => PHP_EOL]) );

    $custom  = $update->message()->from()->getCustom();
    $trigger = $custom->admin_current_trigger;

    // create joke in database
    $item = R::dispense( 'joke' );
    $item->trigger = $trigger;
    $item->note = '';
    $item->joke = $text;
    $item->search = mb_strtolower( $text );
    $item->rank = 0;
    $item->created_at = date('Y-m-d');

    R::store($item);

    $update->answerMessage("Added #$item->id $item->joke");
    return false;
  }

}