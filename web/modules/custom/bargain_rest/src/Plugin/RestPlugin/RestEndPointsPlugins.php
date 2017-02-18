<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;

/**
 * RestEndPointsPlugins class.
 *
 * @RestPlugin(
 *  id = "rest_plugin",
 *  path = "/api",
 *  label = @Translation("Routes list"),
 *  description = @Translation("List of all rest routes")
 * )
 */
class RestEndPointsPlugins extends RestPluginBase {

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'get' => 'get',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Get callback; Return list of plugins.
   */
  protected function get() {
    $plugins = $this->pluginManager->getDefinitions();

    $routes = [];
    // todo: add the available methods to the endpoint.
    foreach ($plugins as $plugin) {
      $routes[$plugin['id']] = [
        'path' => $plugin['path'],
        'label' => $plugin['label'],
        'description' => $plugin['description'],
      ];
    }

    return $routes;
  }

}
