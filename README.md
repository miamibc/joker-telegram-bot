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

Project pages
-------------

* https://github.com/miamibc/joker-telegram-bot
* https://blackcrystal.net/project/joker/

Contact
-------

* miami at blackcrystal dot net
* https://blackcrystal.net