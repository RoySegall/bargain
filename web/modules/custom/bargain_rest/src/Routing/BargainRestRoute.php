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
        array(
          '_controller' => '\Drupal\bargain_rest\Routing\BargainRestRoute::content',
          '_title' => 'Hello'
        ),
        array(
          '_custom_access'  => '\Drupal\bargain_rest\Routing\BargainRestRoute::access',
        ),
        [
          'plugin' => $plugin,
        ]
      );

      // Add the route under the name 'example.content'.
      $route_collection->add($plugin['id'], $route);
    }

    return $route_collection;
  }

  public function content() {
    $plugin_info = \Drupal::routeMatch()->getRouteObject()->getOption('plugin');

    /** @var RestPluginBase $plugin */
    $plugin = \Drupal::service('plugin.manager.rest_plugin')->createInstance($plugin_info['id']);

    return $plugin->callback();
  }

  public function access() {
    $plugin_info = \Drupal::routeMatch()->getRouteObject()->getOption('plugin');

    /** @var RestPluginBase $plugin */
    $plugin = \Drupal::service('plugin.manager.rest_plugin')->createInstance($plugin_info['id']);

    return $plugin->access();
  }

}