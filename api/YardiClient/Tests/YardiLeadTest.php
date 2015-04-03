<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/20/14
 * Time: 4:24 PM
 */

namespace Yardi;

require_once "YardiLead.php";
require_once "YardiXmlDoc.php";

class YardiLeadTest extends \PHPUnit_Framework_TestCase {
  public function testLeadStringTruncation() {
    $lead = new YardiLead();
    $lead->FirstName = self::generateRandomString(rand(60, 70));
    $this->assertLessThanOrEqual(50, strlen($lead->FirstName), 'YardiLead __set function is not truncating values');
  }

  public function testLeadDateFormat() {
    $lead = new YardiLead();
    $lead->ScheduleTourDate = date('F n, Y g:ia T');
    $this->assertRegExp('/\d{2}\/\d{2}\/\d{4}/', $lead->ScheduleTourDate,
      'YardiLead __set function is not converting date format');
  }

  public function testLeadBoolean() {
    $lead = new YardiLead();
    $lead->ScheduleTour = 'True';
    $this->assertTrue($lead->ScheduleTour, 'YardiLead __set function is not asserting boolean values');
  }

  public function testLeadInteger() {
    $lead = new YardiLead();
    $lead->PreferredBedrooms = '10';
    $this->assertEquals(10, $lead->PreferredBedrooms, 'YardiLead __set function is not converting integers');
    $lead->PreferredBedrooms = 'Bob';
    $this->assertEquals(0, $lead->PreferredBedrooms, 'YardiLead __set function is not converting integers');
  }

  public function testStateAbbreviation() {
    $lead = new YardiLead();
    $lead->State = 'Pennsylvania';
    $this->assertEquals('PA', $lead->State, 'YardiLead __set function is not abbreviating states');
    $lead->State = 'west virginia';
    $this->assertEquals('WV', $lead->State, 'YardiLead __set function is not capitalizing state before processing');
  }

  public function testLeadsToString() {
    $leads = new YardiXmlDoc();
    $lead = new YardiLead();
    $lead->SourceName = 'Leads to String Test';
    $lead->ExtReference = '1417645488';
    $lead->PropertyCode = 'a10';
    $lead->FirstName = 'Bob';
    $lead->LastName = 'Test 1';
    $lead->ScheduleTour = 1;
    $lead->ScheduleTourDate = '12/12/2014';
    $lead->PreferredBedrooms = 1;
    $leads->addLead($lead);
    $this->assertEquals('<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL.
      '<ns1:Xmldoc><Leads><Lead><SourceName>Leads to String Test</SourceName>'.
      '<ExtReference>1417645488</ExtReference><PropertyCode>a10</PropertyCode>'.
      '<FirstName>Bob</FirstName>'.
      '<LastName>Test 1</LastName><ScheduleTour>1</ScheduleTour>'.
      '<ScheduleTourDate>12/12/2014</ScheduleTourDate>'.
      '<PreferredBedrooms>1</PreferredBedrooms></Lead></Leads></ns1:Xmldoc>', trim((string)$leads));
    return $leads;
  }

  /**
   * @depends testLeadsToString
   * @param \Yardi\YardiXmlDoc $leads
   */
  public function testLeadsRebuild(YardiXmlDoc $leads) {
    $lead_array = &$leads->getLeads();
    $lead = &current($lead_array);
    $lead->FirstName = 'Larry';
    $lead->LastName = 'Washington';
    $this->assertEquals('<?xml version="1.0" encoding="utf-8"?>'.PHP_EOL.
      '<Leads><Lead><SourceName>Leads to String Test</SourceName>'.
      '<ExtReference>1417645488</ExtReference><PropertyCode>a10</PropertyCode>'.
      '<FirstName>Larry</FirstName>'.
      '<LastName>Washington</LastName><ScheduleTour>1</ScheduleTour>'.
      '<ScheduleTourDate>12/12/2014</ScheduleTourDate>'.
      '<PreferredBedrooms>1</PreferredBedrooms></Lead></Leads>', trim((string)$leads));
  }

  static public function generateRandomString($length = 10) {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, strlen($characters) - 1)];
    }
    return $randomString;
  }
}
 