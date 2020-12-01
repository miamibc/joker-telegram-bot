Joker Telegram Bot plugins
=================

Here you can find library of plugins we use for our own purpose. They are probably not perfect, not optimal, but good to start coding your own plugins for [Joker Telegram Bot](https://github.com/miamibc/joker-telegram-bot).

Plugins are well documented in inline comments, some interesting details will be added here. 

Bash Plugin
-----------

Jokes from Bash.im

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

Corona virus stats for Joker, loaded from [COVID-19 Data Repository by the Center for Systems Science and Engineering (CSSE) at Johns Hopkins University](https://github.com/CSSEGISandData/COVID-19).

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
     

Cowsay Plugin
-----------

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

Log Plugin
-----------

Lurk Plugin
-----------

Moderate Plugin
-----------

Pasta Plugin
-----------

Quote Plugin
-----------

Spotify Plugin
-----------

Sticker Plugin
-----------

Temp Plugin
-----------