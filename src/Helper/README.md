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


