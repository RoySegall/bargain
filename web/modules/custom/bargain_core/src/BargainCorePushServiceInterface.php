<?php

namespace Drupal\bargain_core;

/**
 * Interface BargainCorePushService.
 */
interface BargainCorePushServiceInterface {

  /**
   * Push data to a pusher service.
   *
   * @param string $channel
   *   The channel for push notifications.
   * @param string $event
   *   The event.
   * @param mixed $data
   *   The data of the event.
   */
  public function push($channel, $event, $data);

}
