<?php

namespace Drupal\bargain_core;

/**
 * Common services and methods for other modules.
 */
class BargainCore {

  /**
   * @return mixed
   */
  static public function getPush() {
    return \Drupal::service('bargain_core.push');
  }

}
