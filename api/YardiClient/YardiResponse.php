<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 9:52 AM
 */

namespace Yardi;


class YardiResponse {
  private $client;

  public $resultStatus;
  public $resultMessage;

  public function __construct($response = NULL, $client = NULL) {
    if (isset($response)) {
      $this->parse($response);
    }

    if (isset($client) && $client instanceof \SoapClient) {
      $this->client = $client;
    }
  }

  public function setSoapClient($client) {
    if ($client instanceof \SoapClient) {
      $this->client = $client;
    }
  }

  public function getSoapClient() {
    return $this->client;
  }

  public function parse($response) {
    if (is_array($response)) {
      $result = array_shift($response);
      $xml = $result->any;
      $matches = array();
      if (preg_match('/<ResultStatus>(?<status>[^\>]*)<\/ResultStatus>/', $xml, $matches) != FALSE) {
        $this->resultStatus = $matches['status'];
      }
      if (preg_match('/<ResultMessage>(?<message>[^\>]*)<\/ResultMessage>/', $xml, $matches) != FALSE) {
        $this->resultMessage = $matches['message'];
      }
    }
    elseif (is_string($response)) {
      $matches = array();
      if (preg_match('/<ResultStatus>(?<status>[^\>]*)<\/ResultStatus>/', $response, $matches) != FALSE) {
        $this->resultStatus = $matches['status'];
      }
      if (preg_match('/<ResultMessage>(?<message>[^\>]*)<\/ResultMessage>/', $response, $matches) != FALSE) {
        $this->resultMessage = $matches['message'];
      }
    }
    elseif (is_soap_fault($response)) {
      $this->resultStatus = $response->getCode();
      $this->resultMessage = $response->getMessage();
    }
  }
}

class YardiPingResponse extends YardiResponse {
  public function parse($response) {
    if (is_object($response)) {
      var_dump($response);
      $xml = $response->PingResult->any;
      if (preg_match('/<ResultStatus>(?<status>[^>]*)<\/ResultStatus>/', $xml, $matches) !== FALSE) {
        $this->resultStatus = $matches['status'];
      }
      if (preg_match('/<ResultMessage>(?<message>[^\>]*)<\/ResultMessage>/', $xml, $matches) !== FALSE) {
        $this->resultMessage = $matches['message'];
      }
    }
    else {
      parent::parse($response);
    }
  }
}

class YardiImportGuestResponse extends YardiResponse {
  public function parse($response) {
    if (is_object($response)) {
      $xml = $response->ImportGuestResult->any;
      if (preg_match('/<ResultStatus>(?<status>[^>]*)<\/ResultStatus>/', $xml, $matches) !== FALSE) {
        $this->resultStatus = $matches['status'];
      }
      if (preg_match('/<ResultMessage>(?<message>[^>]*)<\/ResultMessage>/', $xml, $matches) !== FALSE) {
        $this->resultMessage = $matches['message'];
      }
    }
    else {
      parent::parse($response);
    }
  }
}