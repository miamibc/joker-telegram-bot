<?php
/**
 * Kicker plugin for Joker Telegram Bot
 *
 * This plugin will remove users with emojis in their name instantly, and others after 10 minutes of inactivity after join.
 *
 * Options:
 * - `secons_with_emoji` integer, optional, default is 0 - wait time before remove user with emoji in name
 * - `secons_without_emoji` integer, optional, default is 600 - wait time before remove user without emoji in name
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Joker\Plugin;

use Joker\Parser\Update;

class Kicker extends Base
{

  protected $options = [
    'seconds_with_emoji' => 0,
    'seconds_without_emoji' => 600,
  ];

  private $waiting_list = [];

  /**
   * Listen to JOIN event, add to array with time, when to kick this user
   * @param Update $update
   */
  public function onJoin( Update $update )
  {
    // new chat member
    $user = $update->message()->new_chat_member();
    $chat = $update->message()->chat();

    // check name for emoji
    $option  = self::containsEmoji($user->name()) ? 'seconds_with_emoji' : 'seconds_without_emoji';
    $seconds = $this->getOption( $option, 600 );

    // add user to waiting list
    $this->waiting_list[] = [time() + $seconds, $chat->id(), $user->id() ];
  }

  /**
   * Listen to text messages from user and remove from waiting list
   * @param Update $update
   */
  public function onPublicText( Update $update )
  {
    // new chat member
    $message = $update->message();
    foreach ($this->waiting_list as $i=>$item)
    {
      list($time, $chat_id, $user_id ) = $item;
      if (
        $chat_id == $message->chat()->id() &&
        $user_id == $message->from()->id()
      )  unset($this->waiting_list[$i]);
    }
  }

  /**
   * Timer for kicking users from kicklist
   * @param Update $update
   */
  public function onEmpty( Update $update)
  {

    $now = time();
    foreach ($this->waiting_list as $i => $item)
    {

      list($time, $chat_id, $user_id ) = $item;

      if ( $now > $time )
      {

        // kick user
        $update->customRequest('kickChatMember',[
          'chat_id' => $chat_id,
          'user_id' => $user_id,
        ]);

        $update->customRequest('sendMessage',[
          'chat_id' => $chat_id,
          'text'    => 'If it bleeds, we can kill it ;p',
        ]);

        unset($this->waiting_list[$i]);

      }
    }

  }
  /**
   * Check text contains emoji.
   * Full list got from https://unicode.org/emoji/charts/full-emoji-list.html
   * duplicates and strange codes, like \x{00..} has been removed
   *
   * @param $text
   *
   * @return bool
   */
  public static function containsEmoji( $text )
  {
    return preg_match('/\x{1F600}|\x{1F603}|\x{1F604}|\x{1F601}|\x{1F606}|\x{1F605}|\x{1F923}|\x{1F602}|\x{1F642}|\x{1F643}|\x{1F609}|\x{1F60A}|\x{1F607}|\x{1F970}|\x{1F60D}|\x{1F929}|\x{1F618}|\x{1F617}|\x{263A}|\x{1F61A}|\x{1F619}|\x{1F972}|\x{1F60B}|\x{1F61B}|\x{1F61C}|\x{1F92A}|\x{1F61D}|\x{1F911}|\x{1F917}|\x{1F92D}|\x{1F92B}|\x{1F914}|\x{1F910}|\x{1F928}|\x{1F610}|\x{1F611}|\x{1F60F}|\x{1F612}|\x{1F644}|\x{1F62C}|\x{1F925}|\x{1F60C}|\x{1F614}|\x{1F62A}|\x{1F924}|\x{1F634}|\x{1F637}|\x{1F912}|\x{1F915}|\x{1F922}|\x{1F92E}|\x{1F927}|\x{1F975}|\x{1F976}|\x{1F974}|\x{1F92F}|\x{1F920}|\x{1F973}|\x{1F978}|\x{1F60E}|\x{1F913}|\x{1F9D0}|\x{1F615}|\x{1F61F}|\x{1F641}|\x{2639}|\x{1F62F}|\x{1F632}|\x{1F633}|\x{1F97A}|\x{1F626}|\x{1F627}|\x{1F628}|\x{1F630}|\x{1F625}|\x{1F622}|\x{1F62D}|\x{1F631}|\x{1F616}|\x{1F623}|\x{1F61E}|\x{1F613}|\x{1F629}|\x{1F62B}|\x{1F971}|\x{1F624}|\x{1F621}|\x{1F620}|\x{1F92C}|\x{1F608}|\x{1F47F}|\x{1F480}|\x{1F4A9}|\x{1F921}|\x{1F479}|\x{1F47A}|\x{1F47B}|\x{1F47D}|\x{1F47E}|\x{1F916}|\x{1F63A}|\x{1F638}|\x{1F639}|\x{1F63B}|\x{1F63C}|\x{1F63D}|\x{1F640}|\x{1F63F}|\x{1F63E}|\x{1F648}|\x{1F649}|\x{1F64A}|\x{1F48C}|\x{1F498}|\x{1F49D}|\x{1F496}|\x{1F497}|\x{1F493}|\x{1F49E}|\x{1F495}|\x{1F49F}|\x{2763}|\x{1F494}|\x{1F9E1}|\x{1F49B}|\x{1F49A}|\x{1F499}|\x{1F49C}|\x{1F90E}|\x{1F5A4}|\x{1F90D}|\x{1F4AF}|\x{1F4A2}|\x{1F4A5}|\x{1F4A6}|\x{1F573}|\x{1F4A3}|\x{1F4AC}|\x{1F5EF}|\x{1F4AD}|\x{1F4A4}|\x{1F44B}|\x{1F91A}|\x{1F590}|\x{270B}|\x{1F596}|\x{1F44C}|\x{1F90C}|\x{1F90F}|\x{270C}|\x{1F91E}|\x{1F91F}|\x{1F918}|\x{1F919}|\x{1F448}|\x{1F449}|\x{1F446}|\x{1F595}|\x{1F447}|\x{261D}|\x{1F44D}|\x{1F44E}|\x{270A}|\x{1F44A}|\x{1F91B}|\x{1F91C}|\x{1F44F}|\x{1F64C}|\x{1F450}|\x{1F932}|\x{1F91D}|\x{1F64F}|\x{270D}|\x{1F485}|\x{1F933}|\x{1F4AA}|\x{1F9BE}|\x{1F9BF}|\x{1F9B5}|\x{1F9B6}|\x{1F442}|\x{1F9BB}|\x{1F443}|\x{1F9E0}|\x{1FAC0}|\x{1FAC1}|\x{1F9B7}|\x{1F9B4}|\x{1F440}|\x{1F445}|\x{1F444}|\x{1F476}|\x{1F9D2}|\x{1F9D3}|\x{1F474}|\x{1F475}|\x{1F977}|\x{1F934}|\x{1F478}|\x{1F472}|\x{1F9D5}|\x{1F930}|\x{1F931}|\x{1F47C}|\x{1F385}|\x{1F936}|\x{1F483}|\x{1F57A}|\x{1F574}|\x{1F93A}|\x{1F3C7}|\x{26F7}|\x{1F3C2}|\x{1F6C0}|\x{1F6CC}|\x{1F46D}|\x{1F46B}|\x{1F46C}|\x{1F48F}|\x{1F491}|\x{1F46A}|\x{1F5E3}|\x{1F464}|\x{1F465}|\x{1FAC2}|\x{1F463}|\x{1F9B0}|\x{1F9B1}|\x{1F9B3}|\x{1F9B2}|\x{1F435}|\x{1F412}|\x{1F98D}|\x{1F9A7}|\x{1F436}|\x{1F9AE}|\x{1F429}|\x{1F43A}|\x{1F98A}|\x{1F99D}|\x{1F431}|\x{1F981}|\x{1F42F}|\x{1F405}|\x{1F406}|\x{1F434}|\x{1F40E}|\x{1F984}|\x{1F993}|\x{1F98C}|\x{1F9AC}|\x{1F42E}|\x{1F402}|\x{1F403}|\x{1F404}|\x{1F437}|\x{1F416}|\x{1F417}|\x{1F43D}|\x{1F40F}|\x{1F411}|\x{1F410}|\x{1F42A}|\x{1F42B}|\x{1F999}|\x{1F992}|\x{1F418}|\x{1F9A3}|\x{1F98F}|\x{1F99B}|\x{1F42D}|\x{1F401}|\x{1F400}|\x{1F439}|\x{1F430}|\x{1F407}|\x{1F43F}|\x{1F9AB}|\x{1F994}|\x{1F987}|\x{1F428}|\x{1F43C}|\x{1F9A5}|\x{1F9A6}|\x{1F9A8}|\x{1F998}|\x{1F9A1}|\x{1F43E}|\x{1F983}|\x{1F414}|\x{1F413}|\x{1F423}|\x{1F424}|\x{1F425}|\x{1F426}|\x{1F427}|\x{1F54A}|\x{1F985}|\x{1F986}|\x{1F9A2}|\x{1F989}|\x{1F9A4}|\x{1FAB6}|\x{1F9A9}|\x{1F99A}|\x{1F99C}|\x{1F438}|\x{1F40A}|\x{1F422}|\x{1F98E}|\x{1F40D}|\x{1F432}|\x{1F409}|\x{1F995}|\x{1F996}|\x{1F433}|\x{1F40B}|\x{1F42C}|\x{1F9AD}|\x{1F41F}|\x{1F420}|\x{1F421}|\x{1F988}|\x{1F419}|\x{1F41A}|\x{1F40C}|\x{1F98B}|\x{1F41B}|\x{1F41C}|\x{1F41D}|\x{1FAB2}|\x{1F41E}|\x{1F997}|\x{1FAB3}|\x{1F577}|\x{1F578}|\x{1F982}|\x{1F99F}|\x{1FAB0}|\x{1FAB1}|\x{1F9A0}|\x{1F490}|\x{1F338}|\x{1F4AE}|\x{1F3F5}|\x{1F339}|\x{1F940}|\x{1F33A}|\x{1F33B}|\x{1F33C}|\x{1F337}|\x{1F331}|\x{1FAB4}|\x{1F332}|\x{1F333}|\x{1F334}|\x{1F335}|\x{1F33E}|\x{1F33F}|\x{2618}|\x{1F340}|\x{1F341}|\x{1F342}|\x{1F343}|\x{1F347}|\x{1F348}|\x{1F349}|\x{1F34A}|\x{1F34B}|\x{1F34C}|\x{1F34D}|\x{1F96D}|\x{1F34E}|\x{1F34F}|\x{1F350}|\x{1F351}|\x{1F352}|\x{1F353}|\x{1FAD0}|\x{1F95D}|\x{1F345}|\x{1FAD2}|\x{1F965}|\x{1F951}|\x{1F346}|\x{1F954}|\x{1F955}|\x{1F33D}|\x{1F336}|\x{1FAD1}|\x{1F952}|\x{1F96C}|\x{1F966}|\x{1F9C4}|\x{1F9C5}|\x{1F344}|\x{1F95C}|\x{1F330}|\x{1F35E}|\x{1F950}|\x{1F956}|\x{1FAD3}|\x{1F968}|\x{1F96F}|\x{1F95E}|\x{1F9C7}|\x{1F9C0}|\x{1F356}|\x{1F357}|\x{1F969}|\x{1F953}|\x{1F354}|\x{1F35F}|\x{1F355}|\x{1F32D}|\x{1F96A}|\x{1F32E}|\x{1F32F}|\x{1FAD4}|\x{1F959}|\x{1F9C6}|\x{1F95A}|\x{1F373}|\x{1F958}|\x{1F372}|\x{1FAD5}|\x{1F963}|\x{1F957}|\x{1F37F}|\x{1F9C8}|\x{1F9C2}|\x{1F96B}|\x{1F371}|\x{1F358}|\x{1F359}|\x{1F35A}|\x{1F35B}|\x{1F35C}|\x{1F35D}|\x{1F360}|\x{1F362}|\x{1F363}|\x{1F364}|\x{1F365}|\x{1F96E}|\x{1F361}|\x{1F95F}|\x{1F960}|\x{1F961}|\x{1F980}|\x{1F99E}|\x{1F990}|\x{1F991}|\x{1F9AA}|\x{1F366}|\x{1F367}|\x{1F368}|\x{1F369}|\x{1F36A}|\x{1F382}|\x{1F370}|\x{1F9C1}|\x{1F967}|\x{1F36B}|\x{1F36C}|\x{1F36D}|\x{1F36E}|\x{1F36F}|\x{1F37C}|\x{1F95B}|\x{2615}|\x{1FAD6}|\x{1F375}|\x{1F376}|\x{1F37E}|\x{1F377}|\x{1F378}|\x{1F379}|\x{1F37A}|\x{1F37B}|\x{1F942}|\x{1F943}|\x{1F964}|\x{1F9CB}|\x{1F9C3}|\x{1F9C9}|\x{1F9CA}|\x{1F962}|\x{1F37D}|\x{1F374}|\x{1F944}|\x{1F52A}|\x{1F3FA}|\x{1F30D}|\x{1F30E}|\x{1F30F}|\x{1F310}|\x{1F5FA}|\x{1F5FE}|\x{1F9ED}|\x{1F3D4}|\x{26F0}|\x{1F30B}|\x{1F5FB}|\x{1F3D5}|\x{1F3D6}|\x{1F3DC}|\x{1F3DD}|\x{1F3DE}|\x{1F3DF}|\x{1F3DB}|\x{1F3D7}|\x{1F9F1}|\x{1FAA8}|\x{1FAB5}|\x{1F6D6}|\x{1F3D8}|\x{1F3DA}|\x{1F3E0}|\x{1F3E1}|\x{1F3E2}|\x{1F3E3}|\x{1F3E4}|\x{1F3E5}|\x{1F3E6}|\x{1F3E8}|\x{1F3E9}|\x{1F3EA}|\x{1F3EB}|\x{1F3EC}|\x{1F3ED}|\x{1F3EF}|\x{1F3F0}|\x{1F492}|\x{1F5FC}|\x{1F5FD}|\x{26EA}|\x{1F54C}|\x{1F6D5}|\x{1F54D}|\x{26E9}|\x{1F54B}|\x{26F2}|\x{26FA}|\x{1F301}|\x{1F303}|\x{1F3D9}|\x{1F304}|\x{1F305}|\x{1F306}|\x{1F307}|\x{1F309}|\x{2668}|\x{1F3A0}|\x{1F3A1}|\x{1F3A2}|\x{1F488}|\x{1F3AA}|\x{1F682}|\x{1F683}|\x{1F684}|\x{1F685}|\x{1F686}|\x{1F687}|\x{1F688}|\x{1F689}|\x{1F68A}|\x{1F69D}|\x{1F69E}|\x{1F68B}|\x{1F68C}|\x{1F68D}|\x{1F68E}|\x{1F690}|\x{1F691}|\x{1F692}|\x{1F693}|\x{1F694}|\x{1F695}|\x{1F696}|\x{1F697}|\x{1F698}|\x{1F699}|\x{1F6FB}|\x{1F69A}|\x{1F69B}|\x{1F69C}|\x{1F3CE}|\x{1F3CD}|\x{1F6F5}|\x{1F9BD}|\x{1F9BC}|\x{1F6FA}|\x{1F6B2}|\x{1F6F4}|\x{1F6F9}|\x{1F6FC}|\x{1F68F}|\x{1F6E3}|\x{1F6E4}|\x{1F6E2}|\x{26FD}|\x{1F6A8}|\x{1F6A5}|\x{1F6A6}|\x{1F6D1}|\x{1F6A7}|\x{2693}|\x{26F5}|\x{1F6F6}|\x{1F6A4}|\x{1F6F3}|\x{26F4}|\x{1F6E5}|\x{1F6A2}|\x{2708}|\x{1F6E9}|\x{1F6EB}|\x{1F6EC}|\x{1FA82}|\x{1F4BA}|\x{1F681}|\x{1F69F}|\x{1F6A0}|\x{1F6A1}|\x{1F6F0}|\x{1F680}|\x{1F6F8}|\x{1F6CE}|\x{1F9F3}|\x{231B}|\x{23F3}|\x{231A}|\x{23F0}|\x{23F1}|\x{23F2}|\x{1F570}|\x{1F55B}|\x{1F567}|\x{1F550}|\x{1F55C}|\x{1F551}|\x{1F55D}|\x{1F552}|\x{1F55E}|\x{1F553}|\x{1F55F}|\x{1F554}|\x{1F560}|\x{1F555}|\x{1F561}|\x{1F556}|\x{1F562}|\x{1F557}|\x{1F563}|\x{1F558}|\x{1F564}|\x{1F559}|\x{1F565}|\x{1F55A}|\x{1F566}|\x{1F311}|\x{1F312}|\x{1F313}|\x{1F314}|\x{1F315}|\x{1F316}|\x{1F317}|\x{1F318}|\x{1F319}|\x{1F31A}|\x{1F31B}|\x{1F31C}|\x{1F321}|\x{2600}|\x{1F31D}|\x{1F31E}|\x{1FA90}|\x{2B50}|\x{1F31F}|\x{1F320}|\x{1F30C}|\x{2601}|\x{26C5}|\x{26C8}|\x{1F324}|\x{1F325}|\x{1F326}|\x{1F327}|\x{1F328}|\x{1F329}|\x{1F32A}|\x{1F32B}|\x{1F32C}|\x{1F300}|\x{1F302}|\x{2602}|\x{2614}|\x{26F1}|\x{26A1}|\x{2744}|\x{2603}|\x{26C4}|\x{2604}|\x{1F525}|\x{1F4A7}|\x{1F30A}|\x{1F383}|\x{1F384}|\x{1F386}|\x{1F387}|\x{1F9E8}|\x{2728}|\x{1F388}|\x{1F389}|\x{1F38A}|\x{1F38B}|\x{1F38D}|\x{1F38E}|\x{1F38F}|\x{1F390}|\x{1F391}|\x{1F9E7}|\x{1F380}|\x{1F381}|\x{1F397}|\x{1F39F}|\x{1F3AB}|\x{1F396}|\x{1F3C6}|\x{1F3C5}|\x{1F947}|\x{1F948}|\x{1F949}|\x{26BD}|\x{26BE}|\x{1F94E}|\x{1F3C0}|\x{1F3D0}|\x{1F3C8}|\x{1F3C9}|\x{1F3BE}|\x{1F94F}|\x{1F3B3}|\x{1F3CF}|\x{1F3D1}|\x{1F3D2}|\x{1F94D}|\x{1F3D3}|\x{1F3F8}|\x{1F94A}|\x{1F94B}|\x{1F945}|\x{26F3}|\x{26F8}|\x{1F3A3}|\x{1F93F}|\x{1F3BD}|\x{1F3BF}|\x{1F6F7}|\x{1F94C}|\x{1F3AF}|\x{1FA80}|\x{1FA81}|\x{1F3B1}|\x{1F52E}|\x{1FA84}|\x{1F9FF}|\x{1F3AE}|\x{1F579}|\x{1F3B0}|\x{1F3B2}|\x{1F9E9}|\x{1F9F8}|\x{1FA85}|\x{1FA86}|\x{2660}|\x{2665}|\x{2666}|\x{2663}|\x{265F}|\x{1F0CF}|\x{1F004}|\x{1F3B4}|\x{1F3AD}|\x{1F5BC}|\x{1F3A8}|\x{1F9F5}|\x{1FAA1}|\x{1F9F6}|\x{1FAA2}|\x{1F453}|\x{1F576}|\x{1F97D}|\x{1F97C}|\x{1F9BA}|\x{1F454}|\x{1F455}|\x{1F456}|\x{1F9E3}|\x{1F9E4}|\x{1F9E5}|\x{1F9E6}|\x{1F457}|\x{1F458}|\x{1F97B}|\x{1FA71}|\x{1FA72}|\x{1FA73}|\x{1F459}|\x{1F45A}|\x{1F45B}|\x{1F45C}|\x{1F45D}|\x{1F6CD}|\x{1F392}|\x{1FA74}|\x{1F45E}|\x{1F45F}|\x{1F97E}|\x{1F97F}|\x{1F460}|\x{1F461}|\x{1FA70}|\x{1F462}|\x{1F451}|\x{1F452}|\x{1F3A9}|\x{1F393}|\x{1F9E2}|\x{1FA96}|\x{26D1}|\x{1F4FF}|\x{1F484}|\x{1F48D}|\x{1F48E}|\x{1F507}|\x{1F508}|\x{1F509}|\x{1F50A}|\x{1F4E2}|\x{1F4E3}|\x{1F4EF}|\x{1F514}|\x{1F515}|\x{1F3BC}|\x{1F3B5}|\x{1F3B6}|\x{1F399}|\x{1F39A}|\x{1F39B}|\x{1F3A4}|\x{1F3A7}|\x{1F4FB}|\x{1F3B7}|\x{1FA97}|\x{1F3B8}|\x{1F3B9}|\x{1F3BA}|\x{1F3BB}|\x{1FA95}|\x{1F941}|\x{1FA98}|\x{1F4F1}|\x{1F4F2}|\x{260E}|\x{1F4DE}|\x{1F4DF}|\x{1F4E0}|\x{1F50B}|\x{1F50C}|\x{1F4BB}|\x{1F5A5}|\x{1F5A8}|\x{2328}|\x{1F5B1}|\x{1F5B2}|\x{1F4BD}|\x{1F4BE}|\x{1F4BF}|\x{1F4C0}|\x{1F9EE}|\x{1F3A5}|\x{1F39E}|\x{1F4FD}|\x{1F3AC}|\x{1F4FA}|\x{1F4F7}|\x{1F4F8}|\x{1F4F9}|\x{1F4FC}|\x{1F50D}|\x{1F50E}|\x{1F56F}|\x{1F4A1}|\x{1F526}|\x{1F3EE}|\x{1FA94}|\x{1F4D4}|\x{1F4D5}|\x{1F4D6}|\x{1F4D7}|\x{1F4D8}|\x{1F4D9}|\x{1F4DA}|\x{1F4D3}|\x{1F4D2}|\x{1F4C3}|\x{1F4DC}|\x{1F4C4}|\x{1F4F0}|\x{1F5DE}|\x{1F4D1}|\x{1F516}|\x{1F3F7}|\x{1F4B0}|\x{1FA99}|\x{1F4B4}|\x{1F4B5}|\x{1F4B6}|\x{1F4B7}|\x{1F4B8}|\x{1F4B3}|\x{1F9FE}|\x{1F4B9}|\x{2709}|\x{1F4E7}|\x{1F4E8}|\x{1F4E9}|\x{1F4E4}|\x{1F4E5}|\x{1F4E6}|\x{1F4EB}|\x{1F4EA}|\x{1F4EC}|\x{1F4ED}|\x{1F4EE}|\x{1F5F3}|\x{270F}|\x{2712}|\x{1F58B}|\x{1F58A}|\x{1F58C}|\x{1F58D}|\x{1F4DD}|\x{1F4BC}|\x{1F4C1}|\x{1F4C2}|\x{1F5C2}|\x{1F4C5}|\x{1F4C6}|\x{1F5D2}|\x{1F5D3}|\x{1F4C7}|\x{1F4C8}|\x{1F4C9}|\x{1F4CA}|\x{1F4CB}|\x{1F4CC}|\x{1F4CD}|\x{1F4CE}|\x{1F587}|\x{1F4CF}|\x{1F4D0}|\x{2702}|\x{1F5C3}|\x{1F5C4}|\x{1F5D1}|\x{1F512}|\x{1F513}|\x{1F50F}|\x{1F510}|\x{1F511}|\x{1F5DD}|\x{1F528}|\x{1FA93}|\x{26CF}|\x{2692}|\x{1F6E0}|\x{1F5E1}|\x{2694}|\x{1F52B}|\x{1FA83}|\x{1F3F9}|\x{1F6E1}|\x{1FA9A}|\x{1F527}|\x{1FA9B}|\x{1F529}|\x{2699}|\x{1F5DC}|\x{2696}|\x{1F9AF}|\x{1F517}|\x{26D3}|\x{1FA9D}|\x{1F9F0}|\x{1F9F2}|\x{1FA9C}|\x{2697}|\x{1F9EA}|\x{1F9EB}|\x{1F9EC}|\x{1F52C}|\x{1F52D}|\x{1F4E1}|\x{1F489}|\x{1FA78}|\x{1F48A}|\x{1FA79}|\x{1FA7A}|\x{1F6AA}|\x{1F6D7}|\x{1FA9E}|\x{1FA9F}|\x{1F6CF}|\x{1F6CB}|\x{1FA91}|\x{1F6BD}|\x{1FAA0}|\x{1F6BF}|\x{1F6C1}|\x{1FAA4}|\x{1FA92}|\x{1F9F4}|\x{1F9F7}|\x{1F9F9}|\x{1F9FA}|\x{1F9FB}|\x{1FAA3}|\x{1F9FC}|\x{1FAA5}|\x{1F9FD}|\x{1F9EF}|\x{1F6D2}|\x{1F6AC}|\x{26B0}|\x{1FAA6}|\x{26B1}|\x{1F5FF}|\x{1FAA7}|\x{1F3E7}|\x{1F6AE}|\x{1F6B0}|\x{267F}|\x{1F6B9}|\x{1F6BA}|\x{1F6BB}|\x{1F6BC}|\x{1F6BE}|\x{1F6C2}|\x{1F6C3}|\x{1F6C4}|\x{1F6C5}|\x{26A0}|\x{1F6B8}|\x{26D4}|\x{1F6AB}|\x{1F6B3}|\x{1F6AD}|\x{1F6AF}|\x{1F6B1}|\x{1F6B7}|\x{1F4F5}|\x{1F51E}|\x{2622}|\x{2623}|\x{2B06}|\x{2197}|\x{27A1}|\x{2198}|\x{2B07}|\x{2199}|\x{2B05}|\x{2196}|\x{2195}|\x{2194}|\x{21A9}|\x{21AA}|\x{2934}|\x{2935}|\x{1F503}|\x{1F504}|\x{1F519}|\x{1F51A}|\x{1F51B}|\x{1F51C}|\x{1F51D}|\x{1F6D0}|\x{269B}|\x{1F549}|\x{2721}|\x{2638}|\x{262F}|\x{271D}|\x{2626}|\x{262A}|\x{262E}|\x{1F54E}|\x{1F52F}|\x{2648}|\x{2649}|\x{264A}|\x{264B}|\x{264C}|\x{264D}|\x{264E}|\x{264F}|\x{2650}|\x{2651}|\x{2652}|\x{2653}|\x{26CE}|\x{1F500}|\x{1F501}|\x{1F502}|\x{25B6}|\x{23E9}|\x{23ED}|\x{23EF}|\x{25C0}|\x{23EA}|\x{23EE}|\x{1F53C}|\x{23EB}|\x{1F53D}|\x{23EC}|\x{23F8}|\x{23F9}|\x{23FA}|\x{23CF}|\x{1F3A6}|\x{1F505}|\x{1F506}|\x{1F4F6}|\x{1F4F3}|\x{1F4F4}|\x{2640}|\x{2642}|\x{2716}|\x{2795}|\x{2796}|\x{2797}|\x{267E}|\x{203C}|\x{2049}|\x{2753}|\x{2754}|\x{2755}|\x{2757}|\x{3030}|\x{1F4B1}|\x{1F4B2}|\x{2695}|\x{267B}|\x{269C}|\x{1F531}|\x{1F4DB}|\x{1F530}|\x{2B55}|\x{2705}|\x{2611}|\x{2714}|\x{274C}|\x{274E}|\x{27B0}|\x{27BF}|\x{303D}|\x{2733}|\x{2734}|\x{2747}|\x{1F51F}|\x{1F520}|\x{1F521}|\x{1F522}|\x{1F523}|\x{1F524}|\x{1F170}|\x{1F18E}|\x{1F171}|\x{1F191}|\x{1F192}|\x{1F193}|\x{2139}|\x{1F194}|\x{24C2}|\x{1F195}|\x{1F196}|\x{1F17E}|\x{1F197}|\x{1F17F}|\x{1F198}|\x{1F199}|\x{1F19A}|\x{1F201}|\x{1F202}|\x{1F237}|\x{1F236}|\x{1F22F}|\x{1F250}|\x{1F239}|\x{1F21A}|\x{1F232}|\x{1F251}|\x{1F238}|\x{1F234}|\x{1F233}|\x{3297}|\x{3299}|\x{1F23A}|\x{1F235}|\x{1F534}|\x{1F7E0}|\x{1F7E1}|\x{1F7E2}|\x{1F535}|\x{1F7E3}|\x{1F7E4}|\x{26AB}|\x{26AA}|\x{1F7E5}|\x{1F7E7}|\x{1F7E8}|\x{1F7E9}|\x{1F7E6}|\x{1F7EA}|\x{1F7EB}|\x{2B1B}|\x{2B1C}|\x{25FC}|\x{25FB}|\x{25FE}|\x{25FD}|\x{25AA}|\x{25AB}|\x{1F536}|\x{1F537}|\x{1F538}|\x{1F539}|\x{1F53A}|\x{1F53B}|\x{1F4A0}|\x{1F518}|\x{1F533}|\x{1F532}|\x{1F3C1}|\x{1F6A9}|\x{1F38C}|\x{1F308}|\x{26A7}|\x{2620}|\x{E0063}|\x{E0074}|\x{E0077}|\x{E006C}|\x{E0073}|\x{E007F}/u', $text) ? true : false;
  }

}