<?php

namespace Drupal\bargain_rest\Routing;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class BargainRestRoute {

  public function routes() {

    $plugins = \Drupal::service('plugin.manager.rest_plugin')->getDefinitions();
    $route_collection = new RouteCollection();

    foreach ($plugins as $plugin) {

      $route = new Route(
      // Path to attach this route to:
        $plugin['path'],
        [
          '_controller' => '\Drupal\bargain_rest\Routing\BargainRestRoute::content',
        ],
        [
          '_custom_access'  => '\Drupal\bargain_rest\Routing\BargainRestRoute::access',
        ],
        [
          'plugin' => $plugin,
        ]
      );
      $route_collection->add($plugin['id'], $route);
    }

    return $route_collection;
  }

  /**
   * Trigger the matching callback from the matching plugin.
   *
   * @return mixed
   */
  public function content() {
    $plugin_info = \Drupal::routeMatch()->getRouteObject()->getOption('plugin');

    /** @var RestPluginBase $plugin */
    return \Drupal::service('plugin.manager.rest_plugin')
      ->createInstance($plugin_info['id'])
      ->setArguments()
      ->callback();
  }

  /**
   * Check if the the current user have access to the endpoint.
   *
   * @return AccessResult
   */
  public function access() {
    $plugin_info = \Drupal::routeMatch()->getRouteObject()->getOption('plugin');

    /** @var RestPluginBase $plugin */
    return \Drupal::service('plugin.manager.rest_plugin')
      ->createInstance($plugin_info['id'])
      ->setArguments()
      ->access();
  }

}
