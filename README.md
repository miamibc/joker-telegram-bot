Joker Telegram Bot 
=================

Born in 2001'th this bot was entertaiment chatbot 
written in miRCscript, joking on #blackcrystal Quakenet channel. 

Since that time many things has been changed. Here is third rewrite 
of Joker, made on Telegram to be simple and with PHP to be fun.

Installation and start
-------------------------------------------------

Ensure all required software packages installed

```
# (Ubuntu, Debian)
sudo apt-get install php-cli php-curl php-json git composer
```


Download, install.

```
git clone https://github.com/miamibc/joker-telegram-bot.git
cd joker-telegram-bot
composer install
cp .env.sample .env
```

To run Telegram bot, you must be [registered in BotFather](https://core.telegram.org/bots#6-botfather) 
and have working API token. Insert this token into **joker.php**
or **.env** file before start. Now all is ready to launch.

```
php joker.php
```

Find your bot in Telegram by name and say him **Hi**. 

Plugins
-------

Plugins are used to extend bot functionality.  We made few classic plugins for Joker, feel free to use them and add new. 

To add plugin, create new class extending `Joker\Plugin` and connect it with `$joker->plug( array )` command before main loop. Add methods like `on[Action][Action]( Joker\Event $event )`. These methods will be called when all actions is found in request. Actions can be:

- `message` - requests containing message section
- `sticker` - stickers or replies with sticker
- `text` - contains text
- `public` - public requests
- `private` - non-public requests
- `group` - group, supergroup and channel requests
- `empty` - empty requests

For example, `onPrivateSticker` or `onStickerPrivate` will be called when `sticker` and `private` is found in request.

Parameter of this method can be used get message details and react. For example `$event->answerMessage()` or `$event->answrSticker()` is a shortcut to answer same channel or private user, by message or sticker. 

Return value of plugin method can be:

- `\Joker\Bot::PLUGIN_NEXT` or `true` or `null`- (default) process next plugin in chain
- `\Joker\Bot::PLUGIN_BREAK` or `false` - do not process plugin chain anymore.

Project pages
-------------

* https://github.com/miamibc/joker-telegram-bot
* https://blackcrystal.net/project/joker/

Contact
-------

* miami at blackcrystal dot net
* https://blackcrystal.net