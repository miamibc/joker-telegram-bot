<?php
/**
 *
 * Privacy Plugin
 * Display enabled plugins information: description and risks.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */
namespace Joker\Plugin;

use Joker\Parser\Update;

class Privacy extends Base
{

  protected $options = [
    'description' => 'Reads information about enabled plugins and their privacy',
    'risk' => 'no',
  ];

  public function onText(Update $update)
  {
    $trigger = $update->message()->text()->trigger();
    if (!in_array($trigger, ['privacy'])) return;

    $text = ["Privacy information for enabled plugins:"];

    foreach ($this->bot()->plugins() as $plugin) /** @var Base $plugin */
    {
      $name = get_class($plugin);
      $description = $plugin->getOption('description', 'Not specified');
      $risk = $plugin->getOption('risk', 'Unknown');
      $text[] = "- $name - $description ($risk)";
    }
    $update->replyMessage(implode("\n", $text));
    return false;
  }


}