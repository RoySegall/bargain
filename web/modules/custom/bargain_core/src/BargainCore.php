<?php

namespace Drupal\bargain_core;

/**
 * Common services and methods for other modules.
 */
class BargainCore {

  /**
   * @return \Drupal\bargain_core\BargainCorePush
   */
  static public function getPush() {
    return \Drupal::service('bargain_core.push');
  }

  /**
   * @return \Drupal\bargain_core\BargainCoreEntityFlatten
   */
  static public function getEntityFlatten() {
    return \Drupal::service('bargain_core.entity_flatter');
  }

}
