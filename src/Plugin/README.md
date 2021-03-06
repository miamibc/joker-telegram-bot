Joker Telegram Bot plugins
=================

Here you can find library of plugins we use for our own purpose. They are probably not perfect, not optimal, but good to start coding your own plugins for [Joker Telegram Bot](https://github.com/miamibc/joker-telegram-bot).

Plugins are well documented in inline comments, some interesting details will be added here. 

* [Activity Plugin](#activity-plugin)
* [Bash Plugin](#bash-plugin)
* [Beer Plugin](#beer-plugin)
* [Callback Plugin](#callback-plugin)
* [Carma Plugin](#carma-plugin)
* [Corona Plugin](#corona-plugin)
* [Cowsay Plugin](#cowsay-plugin)
* [Currency Plugin](#currency-plugin)
* [Excuse Plugin](#excuse-plugin)
* [Forwarder Plugin](#forwarder-plugin)
* [Hello Plugin](#hello-plugin)
* [Kicker Plugin](#kicker-plugin)
* [Log Plugin](#log-plugin)
* [Lurk Plugin](#lurk-plugin)
* [Meme Plugin](#meme-plugin)
* [Moderate Plugin](#moderate-plugin)
* [Pasta Plugin](#pasta-plugin)
* [Quote Plugin](#quote-plugin)
* [Server Plugin](#server-plugin)
* [Spotify Plugin](#spotify-plugin)
* [Sticker Plugin](#sticker-plugin)
* [Temp Plugin](#temp-plugin)
* [Twitch Plugin](#twitch-plugin)
* [UrlCollector Plugin](#urlcollector-plugin)
* [Whynot Plugin](#whynot-plugin)

## Activity Plugin

Stores user activity from messages containing `from` field. Data will be stored in sqlite database, table `user`.

- User ID
- Username
- Full name
- Last message time
- Last message ID

Configuration options:
- `sync_time` (integer, default 60) - seconds, how often to write data to database

## Bash Plugin

Random jokes from [Bash.im](https://bash.im/).

Ask random joke, or search by id or text:

    !bash
    !bash 1234
    !bash scuko blya jjosh
    
Bot will answer you with joke from bash

    !bash #268971
    godlike: 
    Good news for the learner, Russian vocabulary consists of about 10% of loan words that you already know (like prablyem for problem or kofe for coffee
    Tellah: 
    or "scuko blya jjosh" for "awesome"

## Beer Plugin

Answers to message with beer thematics, by one of hardcoded joke. 

    <Me> Как бы хотелось холодного пивка с закусочкой
    <Joker> Перед злоупотреблением, охладить

Beer plugin is version of [Pasta Plugin](#pasta-plugin). 
Thanks to [Dm!tro](https://github.com/Dm1tro-in-da-world) for this contribution.  

## Callback Plugin

Plugin for fast prototyping. Allows to bind trigger and a callback as a parameters of plugin initialization.

Example:

```
$joker->plug([
  new Joker\Plugin\Callback(['trigger'=>'callbacktest', 'callback' => function(Joker\Event $event){
    $event->answerMessage('test ok');
    return false;
  }]),
]);
```

## Carma plugin

Allows people to exchange carma between them by like and dislike their posts.

Options:
- `clean_time` (false|integer, optional, default 10)  - false, or seconds to remove mana exchange message
- `power_time` (integer, optional, default 600) - number of seconds to have full power (1)
- `start_carma` (integer, optional, default 10)  - points you start with

Thanks for help in development to **D0b3rm4nN** and [AL^Raven](https://github.com/alravenbc).

## Corona Plugin

Corona worldwide virus stats for Joker.

You can ask last report by providing country and region

    !corona Estonia
    
To exact match of country/region bot answers with data:
    
    Corona situation in Estonia
    Incident rate: 927.83
    Case fatality ratio: 0.96
    Active cases: 5112
    Confirmed cases: 12308
    Recovered cases: 7078
    Deaths: 118
    Last update: 2020-12-01 05:26:18

If no exact match found, you will get list of country/region requests

    Try !corona with more specific query:
    !corona Baden-Wurttemberg, Germany
    !corona Bayern, Germany
    !corona Berlin, Germany
    !corona Brandenburg, Germany
    !corona Bremen, Germany
    !corona Hamburg, Germany
    !corona Hessen, Germany
    !corona Mecklenburg-Vorpommern, Germany
    !corona Niedersachsen, Germany
    !corona Nordrhein-Westfalen, Germany
    !corona Rheinland-Pfalz, Germany
    !corona Saarland, Germany
    !corona Sachsen, Germany
    !corona Sachsen-Anhalt, Germany
    !corona Schleswig-Holstein, Germany
    !corona Thuringen, Germany
    !corona Unknown, Germany
    
To complete action, pick one of this list, for example

    !corona Berlin, Germany
     
Configuration options:
- `file` (string, required) - file where to save data file from github
- `update_hours` (integer, optional, default 3) - hours between update of data from github

Data from [COVID-19 Data Repository by the Center for Systems Science and Engineering (CSSE) at Johns Hopkins University](https://github.com/CSSEGISandData/COVID-19) repository.

## Cowsay Plugin

Classic [linux console fun](https://en.wikipedia.org/wiki/Cowsay) now is in Joker. Say

    !cowsay Moo

Bot will answer:

     < Moo >
          \   ^__^
           \  (oo)\_______
              (__)\       )\/\
                 ||----w |
                 ||     ||
  
After some time, we changed this output to be an image.

![Example image, cow saing Moo](https://raw.githubusercontent.com/miamibc/joker-telegram-bot/master/assets/cowsay9bS19a.png)

Configuration options:
- `font_file`  (string, optional, default depends on ubuntu version) path to font file
- `font_size`  (int, optional, default 20) font size in pixels
- `padding`    (int, optional, default 100) padding
- `bg_color`   (string, optional, default #000000) background color
- `text_color` (string, optional, default #ffffff) text color
- `delete`     (boolean, optional, default true) delete generated image after sending

## Currency Plugin

Currency exchange rates for Joker (thanks ʎǝxǝl∀ for ide∀)

You can ask bot for currency exchange rate

```
!currency BTC USD
``` 
 
And receive information, something like

```
1 BTC = 19354.425 USD
```

Information requested from [Coinbase API](https://developers.coinbase.com/api/v2)

## Excuse Plugin

Generate random exuses

```
!excuse
```

Bot will answer something like

```
Приятель, добрый день. Платеж на обработке. Смогу доделать в конце недели. Я бы с радостью уже все сделал.
```

Ported from [lgg/excuse-generator](https://github.com/lgg/excuse-generator)

## Forwarder Plugin

Forwards messages from one chat to another. Rules can be added with configuration, example:

```
new Joker\Plugin\Forwarder([
    ['from' => -343502518, 'text' => ['*покуп*'], 'to' => -343502519, ],
    ['from' => -343502518, 'text' => ['*прода*', '*сдаё*'], 'to' => -343502519, 'forward' => false ],
]),
```

Each line of configuration consists of array:
- **from** (integer or array of integers, required) one or many chat_id's, to receive messages from 
- **text** (string or array of strings) one or many masks of text to match
- **to** (integer or array of integers) one or many chat_id, to send message to
- **forward** (boolean, optional, default is true) forwards message, or creates a copy of text

NB! Joker can't read and forward messages from another bots, because Telegram [does not allow to read bots messages with Telegram Bot API](https://core.telegram.org/bots/faq#why-doesnt-my-bot-see-messages-from-other-bots). If you need to read them, try to search implementations of another protocol - Mtproto. 

## Hello Plugin

Hello world plugin, small example of writing basic plugin for Joker.

Find your bot in telegram and say him privately:

    /start
    
Bot will answer you with standart greeting

    Hello, Sergei. I'm Joker, the Telegram Bot.
    
    Born in 2001, I was entertainment chatbot written in miRCscript. Now I'm a bit new: I run PHP on fast virtual server to connect modern geeky Telegram network and joke my random funs.
    
    Read more: https://github.com/miamibc/joker-telegram-bot 

## Kicker Plugin

If your channel is popular enough, you will constantly be attacked with bots with strange names containing emoji. 

Add this plugin to kick such users.  

## Log Plugin

Log all incoming messages to a file

Configuration options:
* `empty` (boolean, default false) - log empty messages
* `screen` (boolean, default false) - log messages to the screen
* `file` (string or false, default false) - log messages to file 

## Lurk Plugin

Shows articles from [Lurkmore](https://lurkmore.to/) with use of [Mediawiki API](https://www.mediawiki.org/wiki/API).

You can search topics in Lurkmore

    !lurk мем
    
Bot will find all articles with these words

    Please choose one:
    !lurk Мем
    !lurk Форсед-мем
    !lurk Новый мем
    !lurk Автобус (мем)
    !lurk Не мем
    !lurk Я придумал новый мем
    !lurk Это не мем
    !lurk Это мем
    !lurk Форсед мем
    
Then you can request for article

    !lurk Форсед-мем
    
Answer will be parsed from Wikimedia article (suddenly not the best quality)

    «Превед медвед — другим наука. Превед медвед — какая скука! Превед медвед и день, и ночь, Превед медвед — ни шагу прочь!
    
    »— М. Кронгауз feat. [Наше всё](/%D0%9F%D1%83%D1%88%D0%BA%D0%B8%D0%BD "Пушкин")«Если показывать по ТВ каждый день задницу лошади, она в результате станет популярной
    
    »— Владимир Познер[![](//lurkmore.so/images/thumb/a/ac/Lurkosparta.jpg/250px-Lurkosparta.jpg)](/%D0%A4%D0%B0%D0%B9%D0%BB:Lurkosparta.jpg)[![](/skins/common/images/magnify-clip.png)](/%D0%A4%D0%B0%D0%B9%D0%BB:Lurkosparta.jpg "Увеличить")
    
    Как это обычно бывает у нас…

## Meme Plugin

Create meme with [Memegen.link](https://memegen.link/) project.

For instructions, say

```
!meme
```

Bot will answer you with instructions, generated from API

```
Usage: !meme <name>
then add one, or two lines of text.
Name can be selected from: aag ackbar afraid agnes aint-got-time ams ants apcr atis away awesome awesome-awkward awkward awkward-awesome bad badchoice bd bender bihw biw blb boat both bs buzz captain captain-america cb cbg center ch cheems chosen cmm crazypills cryingfloor db dg disastergirl dodgson doge dragon drake ds dsm dwight elf ermg fa facepalm fbf feelsgood fetch fine firsttry fmr fry fwp gandalf gb gears ggg gru grumpycat hagrid happening harold hipster home icanhas imsorry inigo interesting ive iw jd jetpack joker jw keanu kermit kk kombucha leo live ll lrv mb michael-scott millers mini-keanu mmm money mordor morpheus mw nice noidea ntot oag officespace older oprah patrick persian philosoraptor pigeon ptj puffin red regret remembers rollsafe sad-biden sad-boehner sad-bush sad-clinton sad-obama sadfrog saltbae sarcasticbear sb scc sf sk ski snek soa sohappy sohot soup-nazi sparta spiderman spongebob ss stew stonks stop-it success tenguy toohigh tried trump ugandanknuck whatyear winter wkh wonka worst xy yallgot yodawg yuno zero-wing
```

Example meme

```
!meme rollsafe
Show what you can.
Learn what you don't.
```

![meme answer](https://api.memegen.link/images/rollsafe/Show_what_you_can./Learn_what_you_don't..jpg)

## Moderate Plugin

Removes sticker flood in Group. Bot must be administrator.

Plugin counts amount of text between stickers. If user trying to flood with stickers his stickers will be removed.

Parameter `characters_between` defaults to 255, can be set in plugin options. 

## Pasta Plugin

Example plugin with custom text triggers.

Parameter `minimum_time` can be used to set minimum time between triggering this plugin.

## Quote Plugin

Random joke from collection of our jokes.

Jokes are kept in files, saved in `dir` directory. File name must be `!<trigger>.txt`

When bot founds file, he will answer by random joke from that file, or specific joke by id (number) or performs search. Example:

```
!irc
<Krichek> маям скинь мне джоки

!irc Krichek
<Krichek> маям скинь мне джоки

!irc 1
<Krichek> маям скинь мне джоки
```

To get list of all triggers available, ask this:

```
!list
```

Bot will look jokes directory and answers:

```
List of jokes: !2alsmom !2forsedad !al !anek !cyberzx !ep !fly !fun !gorkiy !hmage !irc !ircnet !joke !jokerquery !kod !lancer !matpac !mind !morg !mt !onliner !patriot !peni !pore !romes !say !test !tg !trigger !ua !vou !wolf
```

To add new joke, you can send it to Joker private chat.

```
SHPONGIk, [01.11.20 21:58]
Димас, ты с концентраторами от моника к ПС знаком?

QQSKA, [01.11.20 21:58]
концентрацептивачто?

SHPONGIk, [01.11.20 21:59]
понятно)

```

If joke is from Telegram chat, it will be converted to well-formed format with date and parsed lines, otherwise will be added as is.

```
Added: !tg 111 of 111: [01.11.20 21:58]
<SHPONGIk> Димас, ты с концентраторами от моника к ПС знаком?
<QQSKA> концентрацептивачто?
<SHPONGIk> понятно)
```

## Server Plugin

With this plugin you can communicate bot from outside.

Start bot with this plugin attached, and try one of these methods of communication:

- **HTTP request** - send command with curl or other HTTP client, HTTP method must be POST, 
  URL can be any supported by [Telegram Bot API](https://core.telegram.org/bots/api#available-methods).

  Example:
  ```
  curl -X POST --data '{"chat_id":"-343502518", "text":"Testing Server Plugin"}' http://127.0.0.1:5566/sendMessage
  ```
  You will receive JSON-formatted response from Telegram API, and new message from bot in your chat.
  
- **Plain JSON** - use Telnet or other network tool, send JSON-formatted message to the bot. 
  Only `sendMessage` can be executed with plain request.
  
  Example:
  ```
  echo '{"chat_id":"-343502518", "text":"Testing Server Plugin"}' | telnet 127.0.0.1 5566
  ```

Configuration options:
- `address` (string, optional, default 127.0.0.1)  - address of interface to listen
- `port` (integer, optional, default 5566) - port number


## Spotify Plugin

Random music track from Spotify API

Ask random track or search:
- !spotify
- !spotify limp bizkit

Bot will answer with random track from the top of results.

```
Listen track Take A Look Around by Limp Bizkit in [Spotify]
``` 

with link to [Take A Look Around by Limp Bizkit](https://open.spotify.com/track/1J1H9lKNHwT0waswoMf8yV)

Configuration options:
- `client_id` (string, required) Spotify client ID
- `secret` (string, required) Spotify client secret

Documentation:
- [Spotify Search API](https://developer.spotify.com/documentation/web-api/reference-beta/#category-search)
- [Spotify Authorization](https://developer.spotify.com/documentation/general/guides/authorization-guide/#client-credentials-flow)

TODO:
- Add fade-in/out effect to audio track https://ffmpeg.org/ffmpeg-filters.html#afade-1
- Publish result as audio message


## Sticker Plugin

Example sticker plugin. Send sticker to Joker private chat, he will answer with random sticker from same pack.

## Temp Plugin

Weather conditions worldwide. Commands to activate this:
- !temp
- !temp moscow
- !temp 59.4525804,24.844022

Example

```
!temp moscow
```

Bot will answer:

```
!temp: -6.8°C, from -10 to -4.44°С, wind 1 m/s, clouds 100%, pressure 1033 hPa, visibility 10000 m, overcast clouds in Moscow RU
```

You can repeat without location

```
!temp
```

Bot answers with weather condition from your last requested location. 

```
!temp: -6.8°C, from -10 to -4.44°С, wind 1 m/s, clouds 100%, pressure 1033 hPa, visibility 10000 m, overcast clouds in Moscow RU
```

If no last location exists, bot will answer with `default` location from options.

Configuration options:
- `default` (string, optional) - default location, by default Tallinn
- `api_key` (string, required) - Openwearther API key

Data source [Openweather API](http://api.openweathermap.org)

## Twitch Plugin

This plugin allows you to search Twitch channels.

To enable plugin:
1. Create your application in [Twitch API](https://dev.twitch.tv/docs/api). 
2. Pass `client_id` and `client_secret` to this plugin options, or set environment variables `TWITCH_CLIENT_ID` and `TWITCH_CLIENT_SECRET`
3. Start the bot.

Configuration options:
- `client_id` (string, optional, default is env variable `TWITCH_CLIENT_ID`) - client_id  of your Twitch API application
- `client_secret` (string, optional, default is env variable `TWITCH_CLIENT_SECRET`) - client secret of your Twitch API application

Thanks for idea to **D0b3rm4nN**.

## UrlCollector Plugin

Collects URLS from public messages

Configuration options:
- `file` (string, oprional, default 'data/urls.txt') - file to save urls to


## Whynot Plugin

Generate otmazki why not ...

```
!whynot
```

Bot will answer something like

```
Товарищ, привет. Я потерял всё с чем обычно гуляю, поэтому сегодня не пойду. Смогу чуть позже. ;-]]]
```

Idea from [lgg/excuse-generator](https://github.com/lgg/excuse-generator).