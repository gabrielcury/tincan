<?php

/**
 * 
 * @author khoa <khoa@go1.com.au>
 */

namespace GO1\LMS\TinCan\Object\Activity;

use GO1\LMS\TinCan\ArrayTrait;
use GO1\LMS\TinCan\TinCanAPI;

class InteractionActivity {
  use ArrayTrait;
  
  /**
   *
   * @see https://github.com/adlnet/xAPI-Spec/blob/master/xAPI.md#interaction-components
   * @var array interactionType => componentList(s)
   */
  private $map = array(
    'choice' => array('choices'),
    'sequencing' => array('choices'),
    'likert' => array('scale'),
    'matching' => array('source', 'target'),
    'performance' => array('steps'),
    'true-false' => array(), 
    'fill-in' => array(), 
    'long-fill-in' => array(), 
    'numeric' => array(), 
    'other' => array()
  );
  
  protected $interactionType;
  
  /**
   *
   * @var array 
   * @todo TinCan API: correctResponsesPattern 
   *       Articulate Story 2: correctResponsePatterns
   */
  protected $correctResponsePatterns;
  
  /**
   *
   * @var string choices|scale|source|target|steps 
   */
  protected $componentList;
  
  /**
   *
   * @var array
   */
  protected $components;
  
  /**
   * 
   * @param string $interactionType
   */
  public function setInteractionType($interactionType) {
    if (in_array($interactionType, TinCanAPI::$interactionTypes)) {
      $this->interactionType = $interactionType;
      $this->addArray(array('interactionType' => $interactionType));
    }
  }
  
  /**
   * 
   * @param string $interactionType
   * @param string $componentList
   * @param array $components
   */
  public function setComponents($interactionType, $componentList, $components) {
    if ($this->validateSupportedMap($interactionType, $componentList) &&
        $this->validateComponents($components)) {
      $this->setInteractionType($interactionType);
      // choices|scale|source|target|step
      $this->componentList = $componentList;
      $this->components = $components;
      
      $componentsArray = array();
      foreach ($components as $component) {
        $componentsArray[] = $component->toArray();
      }
      
      $this->addArray(array($componentList => $componentsArray));
    }
  }
  
  /**
   * 
   */
  protected function validateSupportedMap($interactionType, $componentList) {
    // Exclude true-false, fill-in, long-fill-in, numeric, other
    if (in_array($interactionType, TinCanAPI::$interactionTypes)
        && in_array($componentList, TinCanAPI::$interactionComponentMap[$interactionType])) {
      return TRUE;
    }
    return FALSE;
  }
  
  /**
   * 
   */
  protected function validateComponents($components) {
    foreach ($components as $component) {
      if (!$component instanceof InteractionComponent) {
        return FALSE;
      }
    }
    return TRUE;
  }
  
  /**
   * 
   * @param array $patterns Each pattern is a string
   */
  public function setCorrectResponsePatterns($patterns) {
    $this->correctResponsePatterns = $patterns;
    $this->addArray(array('correctResponsePatterns' => $patterns));
  }
}
