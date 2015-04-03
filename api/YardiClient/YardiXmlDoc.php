<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 10:15 AM
 */

namespace Yardi;
use \DOMDocument;
require_once "YardiLead.php";

class YardiXmlDoc {
  protected $doc;
  protected $leads;
  protected $arrLeads;

  public function __construct() {
    $this->doc = new DOMDocument('1.0', 'utf-8');
    $this->leads = $this->doc->createElement('Leads');
    $this->arrLeads = array();
  }

  public function addLead(YardiLead $lead) {
    if (!isset($this->arrLeads[$lead->id])) {
      $lead->id = time() + count($this->arrLeads); // Unique ID
    }
    $this->arrLeads[$lead->id] = $lead;
  }

  public function removeLead(Yardilead $lead) {
    if (isset($lead->id)) {
      unset($this->arrLeads[$lead->id]);
    }
  }

  public function getDocument() {
    return $this->doc;
  }

  public function getLeads($id = NULL) {
    return isset($id) ? $this->arrLeads[$id] : $this->arrLeads;
  }

  public function build() {
    if ($this->leads->childNodes->length > 0) {
      foreach ($this->leads->childNodes as $child) {
        $this->leads->removeChild($child);
      }
    }

    foreach ($this->arrLeads as $lead) {
      $nLead = $this->doc->createElement('Lead');
      foreach ($lead->getSetAttributeNames() as $name) {
        $nAttribute = $this->doc->createElement($name, $lead->{$name});
        $nLead->appendChild($nAttribute);
      }
      $this->leads->appendChild($nLead);
    }

    if ($this->leads->parentNode !== $this->doc) {
      $this->doc->appendChild($this->leads);
    }
  }

  public function __toString() {
    $this->build();
    //$xmlDoc = $this->doc->createElementNS('YSI.Portal.SeniorHousing.WebServices', 'Xmldoc');
    $xmlDoc = $this->doc->createElement('ns1:Xmldoc');
    $xmlDoc->appendChild($this->leads);
    $this->doc->appendChild($xmlDoc);
    return $this->doc->saveXML();
  }
}