<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Parser;

/**
 * @method int message_id()
 * @method User from()
 * @method Chat sender_chat()
 * @method User forward_from()
 * @method Chat forward_from_chat()
 * @method Message reply_to_message()
 * @method User via_bot()
 * @method integer date()
 * @method string edit_date()
 * @method Chat chat()
 * @method Text text()
 * @method Animation animation()
 * @method Audio audio()
 * @method Document document()
 */
class Message extends Base
{

  protected $wrapper = [
    'from' => User::class,
    'sender_chat' => Chat::class,
    'forward_from' => User::class,
    'forward_from_chat' => Chat::class,
    'reply_to_message' => Message::class,
    'via_bot' => User::class,
    'chat' => Chat::class,
    'text' => Text::class,
    'animation' => Animation::class,
    'audio' => Audio::class,
    'document' => Document::class,
  ];

  public function id()
  {
    return $this->message_id();
  }

  public function entities()
  {
    if (!isset($this->data['entities'])) return false;
    if (isset($this->cache['entities'])) return $this->cache['entities'];
    $result = [];
    foreach ($this->data['entities'] as $entity)
    {
      $result[] = new Entity($entity);
    }
    return $this->cache['entities'] = $result;
  }

}