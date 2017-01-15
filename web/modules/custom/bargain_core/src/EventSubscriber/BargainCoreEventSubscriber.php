<?php

/**
 * @file
 * Contains \Drupal\bargain_core\EventSubscriber\BargainCoreEventSubscriber.
 */

namespace Drupal\bargain_core\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class BargainCoreEventSubscriber implements EventSubscriberInterface {

  /**
   * Initializes bargain core module requirements.
   */
  public function onRequest(GetResponseEvent $event) {
    \Drupal::service('bargain_exchange_rate.pull_exchange_rate')->pull();
  }

  /**
   * Implements EventSubscriberInterface::getSubscribedEvents().
   *
   * @return array
   *   An array of event listener definitions.
   */
  static function getSubscribedEvents() {
    // Set a low value to start as early as possible.
    $events[KernelEvents::REQUEST][] = array('onRequest', -100);

    return $events;
  }

}
