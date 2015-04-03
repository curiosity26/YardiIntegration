<?php
/**
 * Created by PhpStorm.
 * User: alexboyce
 * Date: 11/19/14
 * Time: 11:44 AM
 */

namespace Yardi;


class YardiLead {
  protected $attributes;
  public $id;

  public function __construct(array $attributes = array()) {
    // Init the default attributes
    $this->attributes = array(
      'SourceName' => NULL,
      'ExtReference' => NULL,
      'PropertyCode' => NULL,
      'FirstName' => NULL,
      'LastName' => NULL,
      'Email' => NULL
    );

    if (!empty($attributes)) {
      foreach ($attributes as $name => $value) {
        $this->$name = $value;
      }
    }
  }

  static public function dateFields() {
    return array(
      'DOB',
      'PrefMoveInDate',
      'ScheduleTourDate'
    );
  }

  static public function booleanFields() {
    return array(
      'ScheduleTour'
    );
  }

  static public function integerFields() {
    return array(
      'PreferredBedrooms'
    );
  }

  static public function stringLengths($name = NULL) {
    $lengths = array(
      'SourceName' => 50,
      'ExtReference' => 20,
      'PropertyCode' => 8,
      'FirstName' => 50,
      'MiddleInitial' => 2,
      'LastName' => 100,
      'Salutation' => 50,
      'Address1' => 50,
      'Address2' => 50,
      'Address3' => 50,
      'City' => 40,
      'State' => 2,
      'Zip' => 10,
      'OfficePhone' => 14,
      'HomePhone' => 14,
      'CellPhone' => 14,
      'Fax' => 14,
      'Email' => 60,
      'Relationship' => 50,
      'ProspectFirstName' => 50,
      'ProspectMiddleInitial' => 2,
      'ProspectLastName' => 100,
      'Agent' => 40,
      'InitialActivityType' => 50,
      'Notes' => 1800,
      'CareLevel' => 50,
      'UserDefinedLabel1' => 50,
      'UserField01' => 50,
      'UserDefinedLabel2' => 50,
      'UserField02' => 50,
      'UserDefinedLabel3' => 50,
      'UserField03' => 50,
      'UserDefinedLabel4' => 50,
      'UserField04' => 50,
      'UserDefinedLabel5' => 50,
      'UserField05' => 50,
      'UserDefinedLabel6' => 50,
      'UserField06' => 50,
    );

    return isset($lengths[$name]) ? $lengths[$name] : $lengths;
  }

  static public function formatState($state) {
    $state = ucwords($state);

    $states = array(
      'Alabama'=>'AL',
      'Alaska'=>'AK',
      'Arizona'=>'AZ',
      'Arkansas'=>'AR',
      'California'=>'CA',
      'Colorado'=>'CO',
      'Connecticut'=>'CT',
      'Delaware'=>'DE',
      'Florida'=>'FL',
      'Georgia'=>'GA',
      'Hawaii'=>'HI',
      'Idaho'=>'ID',
      'Illinois'=>'IL',
      'Indiana'=>'IN',
      'Iowa'=>'IA',
      'Kansas'=>'KS',
      'Kentucky'=>'KY',
      'Louisiana'=>'LA',
      'Maine'=>'ME',
      'Maryland'=>'MD',
      'Massachusetts'=>'MA',
      'Michigan'=>'MI',
      'Minnesota'=>'MN',
      'Mississippi'=>'MS',
      'Missouri'=>'MO',
      'Montana'=>'MT',
      'Nebraska'=>'NE',
      'Nevada'=>'NV',
      'New Hampshire'=>'NH',
      'New Jersey'=>'NJ',
      'New Mexico'=>'NM',
      'New York'=>'NY',
      'North Carolina'=>'NC',
      'North Dakota'=>'ND',
      'Ohio'=>'OH',
      'Oklahoma'=>'OK',
      'Oregon'=>'OR',
      'Pennsylvania'=>'PA',
      'Rhode Island'=>'RI',
      'South Carolina'=>'SC',
      'South Dakota'=>'SD',
      'Tennessee'=>'TN',
      'Texas'=>'TX',
      'Utah'=>'UT',
      'Vermont'=>'VT',
      'Virginia'=>'VA',
      'Washington'=>'WA',
      'West Virginia'=>'WV',
      'Wisconsin'=>'WI',
      'Wyoming'=>'WY'
    );


    return isset($states[$state]) ? $states[$state] : FALSE;
  }

  static public function getAllAttributeNames() {
    return array_merge(
      array_keys(self::stringLengths()),
      self::dateFields(),
      self::integerFields(),
      self::booleanFields()
    );
  }

  // Return the names of set attributes
  public function getSetAttributeNames() {
    return array_keys($this->attributes);
  }

  static function isAttributeNameValid($name) {
    $names = self::getAllAttributeNames();
    return isset($names[$name]);
  }

  public function __set($name, $value) {
    if (in_array($name, self::dateFields())) {
      $date = new \DateTime($value);
      $value = $date->format('m/d/Y');
    }
    elseif (in_array($name, self::booleanFields())) {
      $value = boolval($value);
    }
    elseif (in_array($name, self::integerFields())) {
      $value = (int)$value;
    }
    elseif (strlen($value) > self::stringLengths($name)) {
      if ($name == 'State') {
        $value = self::formatState($value);
      }
      else {
        $value = substr($value, 0, self::stringLengths($name));
      }
    }

    $this->attributes[$name] = $value;
  }

  public function __get($name) {
    return isset($this->attributes[$name]) ? $this->attributes[$name] : NULL;
  }

  public function __isset($name) {
    return isset($this->attributes[$name]);
  }

  public function __unset($name) {
    unset($this->attributes[$name]);
  }
} 