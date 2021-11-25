# Joker Telegram Bot helpers

Here you can find library of helpers we use for our own purpose. 
They are probably not perfect, not optimal, but made for needs of project [Joker Telegram Bot](https://github.com/miamibc/joker-telegram-bot).

* [Interval](#interval)
* [Process](#process)
* [Tickometer](#tickometer)
* [Timer](#timer)

## Interval

This helper can be used to create intervals.

This is example of plugin, that starts to send messages every 600 seconds after word 'start' in private or public chat.

```php
namespace Joker\Plugin;

class MyPlugin extends Base
{

    private $helper;

    public function __construct( $options = [])
    { 
        // create Interval
        $this->helper = new Joker\Helper\Interval();
    }
    
    public function onText( Update $update )
    {
        // add interval, when text message is 'start'
        if ((string) $update->message()->text() != 'start') return;   
        $this->helper->add(function() use ($update){
            $update->answerMessage( 'Hello world!');
        }, 600);
    }
    
    public function onEmpty()
    {
        // run interval
        $this->helper->run();
    }
    
}
```

## Process

Process helper, allows creating query with jobs, running one-by-one
with ability to repeat, switch to next item or stop processing any time.

Pool - is array of Tasks
We get one Task from the Pool and call [start] on it
Then, we call [finish] and check the result
- result is `repeat` - repeat process with same item
- result is `next`   - repeat process with next item in pool
- result is `stop`   - stop processing

See [Ytmusic Plugin](https://github.com/miamibc/joker-telegram-bot/blob/master/src/Plugin/Ytmusic.php) for details of usage.

## Tickometer

Tick-o-meter, a tool for registering activity in time period.

See [Advice Plugin](https://github.com/miamibc/joker-telegram-bot/blob/master/src/Plugin/Advice.php) for details of usage.

## Timer

Helper allows to add timer.

This is example of plugin, that answers your 'hello' with 'world' after 5 seconds.


```php
namespace Joker\Plugin;

class MyPlugin extends Base
{

    private $helper;

    public function __construct( $options = [])
    { 
        // create timer
        $this->helper = new Joker\Helper\Timer();
    }
    
    public function onText( Update $update )
    {
        // answer with delay 5 seconds
        if ((string) $update->message()->text() == 'hello')
        {   
            $this->helper->add(function() use ($update){
                $update->answerMessage( 'World in 5 seconds!');
            }, 5);
        }
    }
    
    public function onEmpty()
    {
        // run timer
        $this->helper->run();
    }
    
}
```

## Stemmer/Lemmer

To-do.

Sources:
- https://github.com/neonxp/Stemmer/
- https://github.com/ladamalina/php-lingua-stem-ru
- https://github.com/andyceo/PHP-Porter-Stemmer
- https://github.com/wamania/php-stemmer
- https://yandex.ru/dev/mystem/
- https://github.com/iskander-akhmetov/Highly-Language-Independent-Word-Lemmatization-Using-a-Machine-Learning-Classifier/tree/master/DS_lemm
- https://nlpub.ru/%D0%A0%D0%B5%D1%81%D1%83%D1%80%D1%81%D1%8B
- http://opencorpora.org/
- http://www.solarix.ru/
- 
## Rus to lat

Good one, found in internets...

```php
function rus2lat($string){
    $rus = array('ё','ж','ц','ч','ш','щ','ю','я','Ё','Ж','Ц','Ч','Ш','Щ','Ю','Я','Ъ','Ь','ъ','ь');
    $lat = array('e','zh','c','ch','sh','sh','ju','ja','E','ZH','C','CH','SH','SH','JU','JA','','','','');
    $string = str_replace($rus,$lat,$string);
    $string = strtr($string,
    "АБВГДЕЗИЙКЛМНОПРСТУФХЫЭабвгдезийклмнопрстуфхыэ",
    "ABVGDEZIJKLMNOPRSTUFHIEabvgdezijklmnoprstufhie");
    return $string;
}
```

## FAQ

https://github.com/Koziev/chatbot
https://github.com/Koziev/chatbot/blob/master/data/faq2.txt