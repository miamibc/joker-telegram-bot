# Joker Telegram Bot 

Born in 2001'th this bot was entertaiment chatbot written in miRCscript, joking on #blackcrystal Quakenet channel. 

Since that time many things has been changed. Here is third rewrite of Joker, made for Telegram to be modern and simple, with PHP to be fun.

Currently this bot is used in telegram channel [@blackcrystalnet](https://t.me/blackcrystalnet)

## Installation and start

<details>
<summary>Ubuntu/Debian</summary>

##### Install required software packages
```
sudo apt-get install php-cli php-gd php-json php-curl php-mbstring git composer screen ttf-ubuntu-font-family 
```
</details>
<details>
<summary>CentOS/Red Hat</summary>

##### Install required software packages
```
sudo yum install php-cli php-gd php-json php-curl php-mbstring git composer screen
```

##### Install Ubuntu fonts

This is optional step, Ubuntu font is necessary for one of included plugins. You can change it to any other font in plugin configuration.
```
sudo mkdir -p /usr/share/fonts
wget https://assets.ubuntu.com/v1/0cef8205-ubuntu-font-family-0.83.zip
unzip 0cef8205-ubuntu-font-family-0.83.zip -d /usr/share/fonts/
sudo fc-cache -fv
```
</details>
<details>
<summary>Windows</summary>

1. Install [PHP 7.4](https://windows.php.net/download#php-7.4) with basic extensions `gd`, `json`, `curl`, `mbstring`, or just [XAMPP](https://www.apachefriends.org/download.html)
2. Install [Git](https://git-scm.com/downloads)
3. Install [Composer](https://getcomposer.org/download/)
4. Install [Ubuntu fonts](https://assets.ubuntu.com/v1/0cef8205-ubuntu-font-family-0.83.zip)
</details>

Clone repository and install dependencies.
```
git clone https://github.com/miamibc/joker-telegram-bot.git
cd joker-telegram-bot
composer install
cp .env.sample .env
```

To run Telegram bot, you must be [registered in BotFather](https://core.telegram.org/bots#6-botfather) 
and have working Telegram API token placed in `.env` configuration file or `joker.php` directly.

Now we are ready to start.
```
php joker.php
```

Find your bot in Telegram by name and say him **Hi**. 

Use `screen` command, to run bot in background.

## Plugins

By default bot does nothing, [plugins](https://github.com/miamibc/joker-telegram-bot/tree/master/src/Plugin) is used to extend functionality and interact with users.  We made few classic plugins for Joker, like [Hello](https://github.com/miamibc/joker-telegram-bot/blob/master/src/Plugin/Hello.php) and more complex one [Temp](https://github.com/miamibc/joker-telegram-bot/blob/master/src/Plugin/Temp.php), feel free to use them and add new. 

To add plugin, create new class extending `Joker\Plugin` and connect it with `$joker->plug( array )` command before main loop. Add methods like `on[Action][Action]( Joker\Event $event )`. These methods will be called when all actions is found in request. Actions can be:

- `public` - public requests
- `private` - non-public requests
- `group` - group, supergroup and channel requests
- `message` - requests containing message section, this is always true
- `sticker` - stickers or replies with sticker
- `text` - has text
- `photo` - has photo
- `caption` - has caption, usually on photo
- `animation` - has animation
- `audio` - has audio
- `document` - has document
- `video` - has video
- `voice` - has voice
- `contact` - has contact
- `dice` - is a rolled dice
- `game` - contains game
- `reply` - is a reply
- `forward` - is a forwarded message
- `poll` - polls
- `edit` - edited message
- `location` - location
- `join` - user joined the chat
- `leave` - user leaves the chat
- `pin` - new pinned message
- `entities` - has entities attached  
- `empty` - empty requests

For example, `onPrivateSticker` or `onStickerPrivate` will be called when both `sticker` and `private` is found in request.

Parameter of this method is used to access message details and react. For example `$event->answerMessage()` or `$event->answerSticker()` is a shortcut to answer same channel or private user, by message or sticker. Other actions can be found in `Joker\Event` class.

Return value of plugin method can be:

- `Joker\Bot::PLUGIN_NEXT` or `true` or `null`- (default) process next plugin in chain
- `Joker\Bot::PLUGIN_BREAK` or `false` - do not process plugin chain anymore.

More information about existing plugins functionality can be found [here](https://github.com/miamibc/joker-telegram-bot/blob/master/src/Plugin/README.md).

I'll be glad to see your plugins and help with implementations.

## Todo

- Reboot counter
- Restart plugins without lose their state
- Reload plugins without actual restart
- Add onTimer event type, to process events by time
- Add CommandPlugin base class, to simplify adding new commands
- Database implementations: Redis, Mysql, Simplesql and nosql
- Audio processing with ffmpeg and sending with sendAudio/sendVoice
- ~~CowsayPlugin post image instead of text~~ done
- Improved admin of jokes
- Implement [Payments](https://core.telegram.org/bots/payments)
- ~~Currency rates~~ (thanks ʎǝxǝl∀ for ide∀)
- ~~Corona plugin~~
- More cool plugins

Please send your ideas into the [issues](https://github.com/miamibc/joker-telegram-bot/issues)

## Project pages

* https://github.com/miamibc/joker-telegram-bot
* https://blackcrystal.net/project/joker/

## Contributors

* Sergei Miami <miami@blackcrystal.net>
* Dm!tro <dima@aseri.net>