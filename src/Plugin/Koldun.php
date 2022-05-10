<?php
/**
 * Koldun plugin for Joker
 * Performs search, founds "zero result" in google and yandex, and extracts data from it.
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use DiDom\Document;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Psr\Http\Message\ResponseInterface;
use GuzzleHttp\Exception\RequestException;
use Joker\Parser\Update;

class Koldun extends Base
{

  private $client;       // http client
  protected $options =[
    'triggers' => ['сколько', "что", "кто", "как", "где", "почему", "когда", "кому", "зачем"],
  ];

  public function __construct($options = [])
  {
    parent::__construct($options);

    // initialize http client
    $this->client = new Client([
      'timeout'  => 2.0,
      'headers' => [
        'User-Agent' => 'Mozilla/5.0 (X11; Linux x86_64; rv:94.0) Gecko/20100101 Firefox/94.0',
      ],
    ]);

  }

  public function onPublicText( Update $update )
  {
    $text = $update->message()->text();
    if (!in_array( $text->trigger(), $this->getOption('triggers'))) return;

    $google = $this->client->getAsync('https://www.google.com/search?' . http_build_query(['q'=>(string)$text]) );
    $update->bot()->log( "Google started");
    $google->then(
      function (ResponseInterface $res) use ($update){
        $update->bot()->log( "Google status " . $res->getStatusCode() );
        file_put_contents("data/google.html", $html = $res->getBody());
        if (!preg_match('@<div class="\w+" data-attrid="wa:\/description".*<\/div>@iU', $html )) return;
        $didom = new Document("<body>$html</body>");
        $text = $didom->first('div[data-attrid="wa:/description"]')->text();
        $update->answerMessage("Google said: $text");
      },
      function (RequestException $e)  use ($update) {
        $update->bot()->log( "Google error " . $e->getMessage() );
      }
    );
/*
    // $yandex = $this->client->getAsync('https://yandex.ru/search/?' . http_build_query(['lr' => 11481, 'text'=>(string)$text, ]) );
    $yandex = $this->client->getAsync('https://yandex.ru/search/xml?' . http_build_query([
      'user' => 'miamibc',
      'key'=>'03.32681275:2af78167ff7d380aad89e871dd22f708',
      'query'=>(string)$text,
    ]));
    $update->bot()->console( "Yandex started");
    $yandex->then(
      function (ResponseInterface $res) use ($update){
        $update->bot()->console( "Yandex status " . $res->getStatusCode() );
        file_put_contents("data/yandex.html", $res->getBody());
        $didom = new Document($res->getBody());
        $text = $didom->first('div.fact-answer]')->text();
        $update->answerMessage("Google said: $text");
      },
      function (RequestException $e)  use ($update) {
        $update->bot()->console( "Yandex error " . $e->getMessage() );
      }
    );
*/


    $google->wait();
    // $yandex->wait();

  }


}