<?php

namespace Drupal\bargain_core;

/**
 * Interface BargainCorePushService.
 */
interface BargainCorePushService {

  /**
   * Push data to a pusher service.
   *
   * @param $channel
   *   The channel for push notifications.
   * @param $event
   *   The event.
   * @param $data
   *   The data of the event.
   */
  public function push($channel, $event, $data);

}
