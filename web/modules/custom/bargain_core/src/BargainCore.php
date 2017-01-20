<?php

namespace Drupal\bargain_core;

/**
 * Common services and methods for other modules.
 */
class BargainCore {

  /**
   * Alias for the wbsocket push service.
   *
   * @return \Drupal\bargain_core\BargainCorePush
   *   The push notification service.
   */
  static public function getPush() {
    return \Drupal::service('bargain_core.push');
  }

  /**
   * Alias for the entity flatten service.
   *
   * @return \Drupal\bargain_core\BargainCoreEntityFlatten
   *   The entity flatten service.
   */
  static public function getEntityFlatten() {
    return \Drupal::service('bargain_core.entity_flatter');
  }

}
