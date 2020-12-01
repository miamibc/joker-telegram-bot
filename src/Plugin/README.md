Joker Telegram Bot plugins
=================

Here you can find library of plugins we use for our own purpose. They are probably not perfect, not optimal, but good to start coding your own plugins for [Joker Telegram Bot](https://github.com/miamibc/joker-telegram-bot).

Plugins are well documented in inline comments, some interesting details will be added here. 

Bash Plugin
-----------

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

 
Beer Plugin
-----------

Answers to message with beer thematics, by one of hardcoded joke. 

    <Me> Как бы хотелось холодного пивка с закусочкой
    <Joker> Перед злоупотреблением, охладить

Beer plugin is version of [Pasta](https://github.com/miamibc/joker-telegram-bot/tree/master/src/Plugin#pasta-plugin) plugin. 
Thanks to [Dm!tro](https://github.com/Dm1tro-in-da-world) for this contribution.  

Corona Plugin
-----------

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
     
Data from [COVID-19 Data Repository by the Center for Systems Science and Engineering (CSSE) at Johns Hopkins University](https://github.com/CSSEGISandData/COVID-19) repository.

Cowsay Plugin
-----------

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


Currency Plugin
-----------

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

Hello Plugin
-----------

Hello world plugin, small example of writing basic plugin for Joker.

Find your bot in telegram and say him privately:

    /start
    
Bot will answer you with standart greeting

    Hello, Sergei. I'm Joker, the Telegram Bot.
    
    Born in 2001, I was entertainment chatbot written in miRCscript. Now I'm a bit new: I run PHP on fast virtual server to connect modern geeky Telegram network and joke my random funs.
    
    Read more: https://github.com/miamibc/joker-telegram-bot 

Log Plugin
-----------

Log all incoming messages to a file

Lurk Plugin
-----------

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
 

Moderate Plugin
-----------

Removes sticker flood in Group. Bot must be administrator.

Plugin counts amount of text between stickers. If user trying to flood with stickers his stickers will be removed.

Parameter `characters_between` defaults to 255, can be set in plugin options. 

Pasta Plugin
-----------

Example plugin with custom text triggers.

Quote Plugin
-----------

Random joke from collection of our jokes.

Jokes are kept in files, in `data/jokes` directory. File name is `!<trigger>.txt`

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

Spotify Plugin
-----------

Random music track from Spotify API

Ask random track or search:
- !spotify
- !spotify limp bizkit

Bot will answer with random track from the top of results.

Documentation:
- Spotify Search API https://developer.spotify.com/documentation/web-api/reference-beta/#category-search
- Spotify Authorization https://developer.spotify.com/documentation/general/guides/authorization-guide/#client-credentials-flow

TODO:
- Add fade-in/out effect to audio track https://ffmpeg.org/ffmpeg-filters.html#afade-1
- Publish result as audio message


Sticker Plugin
-----------

Send sticker to Joker private chat, he will answer with random sticker from same pack.

Temp Plugin
-----------

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

Data source [Openweather API](http://api.openweathermap.org)