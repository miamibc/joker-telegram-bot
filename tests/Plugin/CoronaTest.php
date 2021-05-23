<?php
/**
 *
 * @package joker-telegram-bot
 * @author Sergei Miami <miami@blackcrystal.net>
 */

namespace Tests\Plugin;

use Joker\Plugin\Corona;
use PHPUnit\Framework\TestCase;

class CoronaTest extends TestCase
{

  public function testDownloadAndSearch()
  {

    $tempfile = tempnam(sys_get_temp_dir(), 'test');

    $plugin = new Corona( ['file'=>$tempfile]);
    $this->assertTrue( $plugin->download_csse_covid_19_data() );

    $this->assertFileExists($tempfile);
    $this->assertFileIsReadable($tempfile);

    $this->assertSame([
      'FIPS',
      'Admin2',
      'Province_State',
      'Country_Region',
      'Last_Update',
      'Lat',
      'Long_',
      'Confirmed',
      'Deaths',
      'Recovered',
      'Active',
      'Combined_Key',
      'Incident_Rate',
      'Case_Fatality_Ratio',
    ], array_keys( $result = $plugin->search_csse_covid_19_data( 'Estonia' ) ));

    $this->assertSame( 'Estonia', $result['Country_Region']);
    $this->assertSame( 'Estonia', $result['Combined_Key']);

    unlink($tempfile);
  }
}
