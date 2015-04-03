<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 4:38 PM
 */

namespace Yardi;
require_once "YardiRequest.php";
class YardiRequestTest extends \PHPUnit_Framework_TestCase {

  public function testSoap() {
    $request = new YardiPingRequest();
    $request->setURL('https://www.iyardiasp.com/9925seniorhousing/WebServices/GuestCardSenior.asmx?wsdl')
      ->setUsername('yardiuser')
      ->setPassword('yardi123')
      ->setServer('aspdb02')
      ->setDatabase('adwgyos_conv');

    $client = new \SoapClient($request->getURL(), array('trace' => 1));
    $client->__setLocation($request->getURL());
    $params = $request->build();

    $response = $client->__soapCall($request->getFunction(), array('parameters' => $params),
      array('soapAction' => 'YSI.Portal.SeniorHousing.WebServices/'.$request->getFunction()));
    $r = new YardiPingResponse($response);
    $this->assertNotEmpty($r->resultStatus);
  }
}
 