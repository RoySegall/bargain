<?php

/**
 * @file
 * Contains \Drupal\bargain_core\EventSubscriber\BargainCoreEventSubscriber.
 */

namespace Drupal\bargain_core\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\KernelEvents;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\DomCrawler\Crawler;


class BargainCoreEventSubscriber implements EventSubscriberInterface {

  /**
   * Initializes bargain core module requirements.
   */
  public function onRequest(GetResponseEvent $event) {
    $source = \Drupal::config('bargain_core.database')->get('currency_source');

    $request = \Drupal::httpClient()->get($source);
    $crawler = new Crawler($request->getBody(true)->getContents());
    $results = [];

    $crawler->filterXPath('//CURRENCIES//CURRENCY')->each(function (Crawler $node, $i) use(&$results) {
      $node = $node->getNode(0);

      $item = [];
      foreach (['NAME', 'UNIT', 'CURRENCYCODE', 'COUNTRY', 'RATE', 'CHANGE'] as $key) {
        $item[$key] = $node->getElementsByTagName($key)->item(0)->nodeValue;
      }

      $results[] = $item;

    });

    $date = $crawler->filterXPath('//CURRENCIES//LAST_UPDATE')->text();
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
