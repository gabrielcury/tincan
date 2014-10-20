<?php

/**
 * 
 * @author khoa <khoa@go1.com.au>
 */

namespace GO1\LMS\TinCan\Object;

use GO1\LMS\TinCan\Object\InverseIdentity\InverseIdentity;
use GO1\LMS\TinCan\Object\Actor\Agent;
use GO1\LMS\TinCan\Object\Actor\GroupBase;
use GO1\LMS\TinCan\Object\Actor\AnonymousGroup;
use GO1\LMS\TinCan\Object\Actor\IdentifiedGroup;

class ObjectFactory implements ObjectFactoryInterface {

  /**
   * @{inheritdoc}
   */
  public function createInverseIdentity($type, $value) {
    return new InverseIdentity($type, $value);
  }

  /**
   * @{inheritdoc}
   */
  public function createActor($type, InverseIdentity $id = NULL, $name = NULL, $members = NULL) {
    if ($type == GroupBase::OBJECT_TYPE) {
      if (is_null($id) && !empty($members)) {
        return new AnonymousGroup($members, $name);
      }
      if ($id instanceof InverseIdentity) {
        return new IdentifiedGroup($id, $name, $members);
      }
    }
    if ($type == Agent::OBJECT_TYPE && $id instanceof InverseIdentity) {
      return new Agent($id, $name);
    }
  }

  
}
