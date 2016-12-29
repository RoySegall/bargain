<?php

namespace Drupal\bargain_core;

/**
 * Interface BargainCorePushService.
 *
 * @package Drupal\bargain_core
 */
interface BargainCorePushService {

  /**
   * Push data to a pusher service.
   */
  public function push();

}
