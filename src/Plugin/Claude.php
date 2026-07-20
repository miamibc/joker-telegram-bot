<?php

/**
 * Claude (Anthropic) Plugin
 *
 * Add chatting ability to your bot with help of [Anthropic Claude API](https://www.anthropic.com/).
 *
 * To start plugin, you need to have account on [Anthropic Console](https://console.anthropic.com/). Insert API key
 * to the .env file, like this:
 *
 * - `ANTHROPIC_API_KEY` your api token
 *
 * Or provide `api_key` initialization parameter.
 *
 * Context modes:
 * - `reply_chain` (default) — walk the Telegram reply chain like the OpenAi plugin. Each conversation thread is
 *   isolated naturally; messages from other users that happen to be in the same thread are included for context.
 * - `user_only` — walk the reply chain but include only messages from the current author + Claude's own replies.
 *   Messages from other participants in the same thread are ignored. Cheapest, most private mode.
 * - `shared` — keep a ring buffer of last N messages of the whole chat. Claude sees the whole conversation when
 *   it answers. Most expensive, but understands group context.
 *
 * Here are all parameters you can customize:
 *
 * Plugin options:
 * - `context_length` (integer, optional, default 4000) — maximum characters of context passed to the API
 * - `context_mode` (string, optional, default 'reply_chain') — 'reply_chain' | 'user_only' | 'shared'
 * - `shared_buffer` (integer, optional, default 50) — how many last messages to keep per chat in 'shared' mode
 * - `triggers` (string, optional, default 'claude|клод|клавдий|клавдия') — pipe-separated trigger words (regex alternation)
 * - `continue_replies` (bool, optional, default true) — if message in reply chain is from the bot, treat it as a trigger
 * - `premium_only` (bool, optional, default false) — answer only to premium accounts
 *
 * Anthropic API options:
 * - `api_key` (string, optional, default from env ANTHROPIC_API_KEY)
 * - `system` (string|array, optional) — system prompt(s). Always sent in Anthropic's separate `system` field.
 * - `model` (string, optional, default 'claude-sonnet-4-6')
 * - `max_tokens` (integer, optional, default 1024)
 * - `effort` (string, optional, default 'low') — 'low'|'medium'|'high'|'max'|null. Not supported by Haiku 4.5.
 * - `thinking_mode` (string, optional, default 'disabled') — 'disabled' or 'adaptive'
 * - `cache_system` (bool, optional, default true) — add cache_control to system prompt (works above 2048 tokens on Sonnet)
 * - `anthropic_version` (string, optional, default '2023-06-01')
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use GuzzleHttp\Client;
use Joker\Exception;
use Joker\Helper\Strings;
use Joker\Parser\Update;

class Claude extends Base
{

  private $client;
  private $context = [];   // message_id => Message, for multi-level reply chain walking
  private $shared  = [];   // chat_id    => [ {role, name, text}, ... ] ring buffer for 'shared' mode
  private $started;
  private $stats = [
    'requests_count' => 0,
    'last_activity'  => 0,
    'input_tokens'   => 0,
    'output_tokens'  => 0,
    'cache_read'     => 0,
    'cache_creation' => 0,
  ];

  protected $options = [
    // plugin options
    'context_length'   => 4000,
    'context_mode'     => 'reply_chain',
    'shared_buffer'    => 50,
    'triggers'         => 'claude|клод|клавдий|клавдия',
    'continue_replies' => true,
    'premium_only'     => false,

    // Anthropic API options
    'api_key'           => null,
    'system'            => 'You are Claude, a witty AI assistant in a Telegram group chat. Be concise, friendly and helpful. Keep replies short unless asked otherwise.',
    'model'             => 'claude-sonnet-4-6',
    'max_tokens'        => 1024,
    'effort'            => 'low',
    'thinking_mode'     => 'disabled',
    'cache_system'      => true,
    'anthropic_version' => '2023-06-01',

    // privacy information
    'description' => 'Adds chatting ability with Claude (Anthropic) AI',
    'risk'        => 'Context [your messages and dialogue in replies] is sent to Anthropic API and processed there. See privacy at https://www.anthropic.com/legal/privacy',
  ];

  public function init()
  {
    if (!$api_key = $this->getOption('api_key', getenv('ANTHROPIC_API_KEY')))
      throw new Exception('API key required to start Claude plugin, please define ANTHROPIC_API_KEY env variable, or `api_key` parameter');

    $this->client = new Client([
      'base_uri' => 'https://api.anthropic.com/',
      'headers'  => [
        'Content-Type'      => 'application/json',
        'x-api-key'         => $api_key,
        'anthropic-version' => $this->getOption('anthropic_version'),
      ],
      'timeout' => 30,
    ]);

    $this->started = time();
  }

  public function onText(Update $update)
  {
    $text = $update->message()->text();

    // commands
    if ($text->trigger() === 'claude')
    {
      switch ($text->token(1, 1))
      {
        case 'parameters':
        case 'params':
          $update->replyMessage(implode(PHP_EOL, [
            "model => "        . $this->getOption('model'),
            "max_tokens => "   . $this->getOption('max_tokens'),
            "effort => "       . ($this->getOption('effort') ?: 'default'),
            "thinking => "     . $this->getOption('thinking_mode'),
            "context_mode => " . $this->getOption('context_mode'),
          ]));
          return false;
        case 'usage':
        case 'stats':
          $update->replyMessage(implode(PHP_EOL, [
            "started => "        . Strings::timeElapsed(date('Y-m-d', $this->started)),
            "last_activity => "  . ($this->stats['last_activity'] ? Strings::diffTimeInWords($this->stats['last_activity'], time()).' ago' : 'Never'),
            "requests_count => " . $this->stats['requests_count'],
            "input_tokens => "   . $this->stats['input_tokens'],
            "output_tokens => "  . $this->stats['output_tokens'],
            "cache_read => "     . $this->stats['cache_read'],
            "cache_creation => " . $this->stats['cache_creation'],
          ]));
          return false;
      }
    }

    if ($this->getOption('premium_only') && !$update->message()->from()->is_premium()) return;

    $mode = $this->getOption('context_mode');

    // 'shared' mode keeps a per-chat buffer of every text message; record this one before deciding to answer
    if ($mode === 'shared')
    {
      $this->recordShared($update->message(), $update->bot()->id());
    }

    [$shouldReply, $messages] = ($mode === 'shared')
      ? $this->buildFromShared($update)
      : $this->buildFromReplyChain($update, $mode === 'user_only');

    if (!$shouldReply || empty($messages)) return;

    $payload = [
      'model'      => $this->getOption('model'),
      'max_tokens' => (int)$this->getOption('max_tokens'),
      'system'     => $this->buildSystem(),
      'messages'   => $messages,
    ];

    $thinking = $this->getOption('thinking_mode');
    if ($thinking && $thinking !== 'disabled')
    {
      $payload['thinking'] = ['type' => $thinking];
    }

    if ($effort = $this->getOption('effort'))
    {
      $payload['output_config'] = ['effort' => $effort];
    }

    $update->message()->chat()->sendAction(Update::ACTION_TYPING);
    $this->bot()->log($payload);

    try
    {
      $raw = $this->client->post('/v1/messages', ['json' => $payload])->getBody()->getContents();
    }
    catch (\Exception $e)
    {
      $this->bot()->log('Claude API error: ' . $e->getMessage());
      return false;
    }

    $response = json_decode($raw);
    $update->bot()->log($response);

    $answer = '';
    if (isset($response->content) && is_array($response->content))
    {
      foreach ($response->content as $block)
      {
        if (($block->type ?? null) === 'text' && !empty($block->text))
        {
          $answer = $block->text;
          break;
        }
      }
    }
    if ($answer === '') return false;

    $reply = $update->replyMessage($answer, ['parse_mode' => 'Markdown']);

    // remember messages so we can walk past the single-level reply_to_message Telegram gives us
    $this->context[$update->message()->id()] = $update->message();
    if ($reply) $this->context[$reply->id()] = $reply;

    if ($mode === 'shared')
    {
      $this->pushShared($update->message()->chat()->id(), 'assistant', 'Claude', $answer);
    }

    $this->stats['requests_count']++;
    $this->stats['last_activity'] = time();
    if (isset($response->usage->input_tokens))              $this->stats['input_tokens']   += $response->usage->input_tokens;
    if (isset($response->usage->output_tokens))             $this->stats['output_tokens']  += $response->usage->output_tokens;
    if (isset($response->usage->cache_read_input_tokens))   $this->stats['cache_read']     += $response->usage->cache_read_input_tokens;
    if (isset($response->usage->cache_creation_input_tokens)) $this->stats['cache_creation'] += $response->usage->cache_creation_input_tokens;

    return false;
  }

  private function buildSystem()
  {
    if (!$sys = $this->getOption('system')) return null;
    if (is_string($sys)) $sys = [$sys];

    $blocks = [];
    foreach ($sys as $t)
    {
      $blocks[] = [
        'type' => 'text',
        'text' => strtr( $t, ['%date%' => date(DATE_RFC1123)]),
      ];
    }
    // cache_control on the last block caches everything up to here. Min cacheable prefix is ~2048 tokens on
    // Sonnet 4.6; a short bio silently won't cache. No harm in marking it.
    if ($this->getOption('cache_system') && !empty($blocks))
    {
      $blocks[count($blocks)-1]['cache_control'] = ['type' => 'ephemeral'];
    }
    return $blocks;
  }

  private function buildFromReplyChain(Update $update, bool $userOnly): array
  {
    $current_user_id = $update->message()->from()->id();
    $bot_id          = $update->bot()->id();
    $context_size    = 0;
    $shouldReply     = false;
    $items           = [];

    $message = $update->message();
    do
    {
      $text  = (string)$message->text();
      $is_me = $message->from()->id() == $bot_id;

      if (preg_match('@\b(' . $this->getOption('triggers') . ')\b@iu', $text)) $shouldReply = true;
      if ($is_me && $this->getOption('continue_replies'))                      $shouldReply = true;

      // user_only: drop messages from other participants but always keep bot replies
      $include = true;
      if ($userOnly && !$is_me && $message->from()->id() != $current_user_id) $include = false;

      if ($include)
      {
        $items[] = [
          'role' => $is_me ? 'assistant' : 'user',
          'name' => $is_me ? 'Claude' : $this->prepareName($message->from()->name()),
          'text' => $text,
        ];
      }

      $size = mb_strlen($text);
      if ($size > $this->getOption('context_length'))
      {
        $update->replyMessage('Многовато букав, скипну :p');
        return [false, []];
      }
      $context_size += $size;
      if ($context_size >= $this->getOption('context_length')) break;

      if (!$replied_to = $message->reply_to_message()) break;
    }
    while ($message = $this->context[$replied_to->id()] ?? false);

    if (!$shouldReply || empty($items)) return [false, []];

    return [true, $this->itemsToMessages(array_reverse($items))];
  }

  private function buildFromShared(Update $update): array
  {
    $msg  = $update->message();
    $text = (string)$msg->text();

    $shouldReply = false;
    if (preg_match('@\b(' . $this->getOption('triggers') . ')\b@iu', $text)) $shouldReply = true;
    if ($this->getOption('continue_replies')
        && ($replied = $msg->reply_to_message())
        && $replied->from()->id() == $update->bot()->id())
    {
      $shouldReply = true;
    }

    if (!$shouldReply) return [false, []];

    $chat_id = $msg->chat()->id();
    $buffer  = $this->shared[$chat_id] ?? [];

    return [true, $this->itemsToMessages($buffer)];
  }

  private function recordShared($message, int $bot_id): void
  {
    $is_me = $message->from()->id() == $bot_id;
    $this->pushShared(
      $message->chat()->id(),
      $is_me ? 'assistant' : 'user',
      $is_me ? 'Claude' : $this->prepareName($message->from()->name()),
      (string)$message->text()
    );
  }

  private function pushShared(int $chat_id, string $role, string $name, string $text): void
  {
    if (!isset($this->shared[$chat_id])) $this->shared[$chat_id] = [];
    $this->shared[$chat_id][] = compact('role', 'name', 'text');
    $max = (int)$this->getOption('shared_buffer');
    if (count($this->shared[$chat_id]) > $max)
    {
      $this->shared[$chat_id] = array_slice($this->shared[$chat_id], -$max);
    }
  }

  /**
   * Convert collected items into the Anthropic messages array.
   *   - Anthropic requires strict user/assistant alternation, so we merge consecutive same-role items.
   *   - There's no `name` field in the API, so when several users appear we prefix content with the name.
   *   - The sequence must start with role=user, so we drop any leading assistant entries.
   */
  private function itemsToMessages(array $items): array
  {
    if (empty($items)) return [];

    $userNames = [];
    foreach ($items as $i)
    {
      if ($i['role'] === 'user') $userNames[$i['name']] = true;
    }
    $multipleUsers = count($userNames) > 1;

    $messages = [];
    foreach ($items as $i)
    {
      $content = ($i['role'] === 'user' && $multipleUsers)
        ? $i['name'] . ': ' . $i['text']
        : $i['text'];

      $last = count($messages) - 1;
      if ($last >= 0 && $messages[$last]['role'] === $i['role'])
      {
        $messages[$last]['content'] .= "\n" . $content;
      }
      else
      {
        $messages[] = ['role' => $i['role'], 'content' => $content];
      }
    }

    while (!empty($messages) && $messages[0]['role'] !== 'user')
    {
      array_shift($messages);
    }

    return $messages;
  }

  private function prepareName(string $name): string
  {
    $name = Strings::transliterate($name);
    $name = preg_replace('@[^a-zA-Z0-9_-]@', '', $name);
    return substr($name, 0, 64);
  }

}
