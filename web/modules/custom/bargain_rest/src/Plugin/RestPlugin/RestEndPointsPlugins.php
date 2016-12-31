<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;

/**
 * @RestPlugin(
 *  id = "rest_plugin",
 *  label = @Translation("The plugin ID."),
 *  path = "/api"
 * )
 */
class RestEndPointsPlugins extends RestPluginBase {

  protected $callbacks = [
    'get' => 'get',
  ];

  /**
   * Return access callback for the routes.
   *
   * @return AccessResult
   */
  public function access() {
    return AccessResult::allowed();
  }

  protected function get() {
    return 'a';
  }
}
