<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 2:17 PM
 */

namespace Yardi;
use SebastianBergmann\Exporter\Exception;

require_once "YardiClient.php";
date_default_timezone_set('America/New_York');
class YardiClientTest extends \PHPUnit_Framework_TestCase {

  public function testCreateConnectionA() {
    $client = new YardiClient();
    $client->setURL('https://www.iyardiasp.com/9925seniorhousing/WebServices/GuestCardSenior.asmx?wsdl')
      ->setUsername('yardiuser')
      ->setPassword('yardi123')
      ->setServer('aspdb02')
      ->setDatabase('adwgyos_conv');
    return $client;
  }

  public function testCreateConnectionB() {
    $client = new YardiClient();
    $client->setURL('https://www.iyardiasp.com/9925seniorhousing2/WebServices/GuestCardSenior.asmx?wsdl')
      ->setUsername('yardiuser')
      ->setPassword('yardi123')
      ->setServer('aspdb02')
      ->setDatabase('adwgyos_tc');

    return $client;
  }

  /**
   * @depends testCreateConnectionA
   * @param \Yardi\YardiClient $client
   */
  public function testPingA(YardiClient $client) {
    $response = $client->ping();
    $this->assertEquals('Success', $response->resultStatus, $response->resultMessage);

    return $client;
  }

  /**
   * @depends testCreateConnectionB
   * @param \Yardi\YardiClient $client
   */
  public function testPingB(YardiClient $client) {
    $response = $client->ping();
    $this->assertEquals('Success', $response->resultStatus, $response->resultMessage);
    return $client;
  }

  /**
   * @depends testPingA
   * @param \Yardi\YardiClient $client
   */
  public function testImportGuestA(YardiClient $client) {
    $leads = new YardiXmlDoc();
    $lead = new YardiLead();
    $lead->SourceName = 'YardiClient Test A';
    $lead->ExtReference = time() + rand(0, 1000);
    $lead->PropertyCode = 'a10';
    $lead->FirstName = 'Bob';
    $lead->LastName = 'Washington';
    $lead->Email = 'alex@sageagestrategies.com';
    $lead->ScheduleTour = 1;
    $lead->ScheduleTourDate = '12/12/2014';
    $lead->PreferredBedrooms = 1;
    $leads->addLead($lead);

    $request = new YardiImportGuestRequest(
      $client->getURL(),
      $client->getUsername(),
      $client->getPassword(),
      $client->getDatabase(),
      $client->getServer(),
      $client->getPlatform(),
      $leads
    );
    //$response = $client->importGuest($leads);
    $response = $request->send();
    $this->assertEquals('Success', $response->resultStatus, $response->resultMessage);

  }

  /**
   * @depends testPingB
   * @param \Yardi\YardiClient $client
   */
  public function testImportGuestB(YardiClient $client) {
    $leads = new YardiXmlDoc();
    $lead = new YardiLead();
    $lead->SourceName = 'YardiClient Test B';
    $lead->ExtReference = time() + rand(0, 1000);
    $lead->PropertyCode = 'a10';
    $lead->FirstName = 'Jerry';
    $lead->LastName = 'Jones';
    $lead->Email = 'alex@sageage1.com';
    $lead->ScheduleTour = 1;
    $lead->ScheduleTourDate = '1/12/2015';
    $lead->PreferredBedrooms = 1;
    $leads->addLead($lead);
    $response = $client->importGuest($leads);
    $this->assertEquals('Success', $response->resultStatus, $response->resultMessage);

    return $client;
  }

  /**
   * @depends testPingA
   * @param \Yardi\YardiClient $client
   */
  public function testImportGuestMultiA(YardiClient $client) {
    $leads = self::generateLeads('YardiClient Test Multi A', rand(2, 10));
    $response = $client->importGuest($leads);
    $this->assertEquals('Success', $response->resultStatus, $response->resultMessage);

    return $client;
  }

  /**
   * @depends testPingB
   * @param \Yardi\YardiClient $client
   */
  public function testImportGuestMultiB(YardiClient $client) {
    $leads = self::generateLeads('YardiClient Test Multi B', rand(2, 10));
    $response = $client->importGuest($leads);
    $this->assertEquals('Success', $response->resultStatus, $response->resultMessage);
  }

  static public function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }

  static public function generateLeads($sourceName, $count = 1) {
    $leads = new YardiXmlDoc();
    for ($i = 0; $i < $count; $i++) {
      $lead = new YardiLead();
      $lead->SourceName = $sourceName;
      $lead->ExtReference = time() + rand(0, 1000);
      $lead->PropertyCode = 'a10';
      $lead->FirstName = self::generateRandomString(rand(5, 50));
      $lead->LastName = self::generateRandomString(rand(5, 100));
      $lead->Email = self::generateRandomString().'@'.self::generateRandomString(20).'.com';
      $lead->ScheduleTour = rand(0, 1);
      if ($lead->ScheduleTour == true) {
        $lead->ScheduleTourDate = date('F n, Y g:ia T', time() - rand(0, 86400*30));
      }
      $lead->PreferredBedrooms = rand(0, 3);

      $leads->addLead($lead);
    }

    return $leads;
  }
}
 