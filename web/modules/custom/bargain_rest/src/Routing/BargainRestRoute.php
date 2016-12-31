<?php

namespace Drupal\bargain_rest\Routing;

use Symfony\Component\Routing\Route;
use Symfony\Component\Routing\RouteCollection;

/**
 * Listens to the dynamic route events.
 */
class BargainRestRouteSubscriber {

  public function routes() {
    $route_collection = new RouteCollection();

    $route = new Route(
    // Path to attach this route to:
      '/example',
      // Route defaults:
      array(
        '_controller' => '\Drupal\example\Controller\ExampleController::content',
        '_title' => 'Hello'
      ),
      // Route requirements:
      array(
        '_permission'  => 'access content',
      )
    );
    // Add the route under the name 'example.content'.
    $route_collection->add('example.content', $route);
    return $route_collection;
  }

}