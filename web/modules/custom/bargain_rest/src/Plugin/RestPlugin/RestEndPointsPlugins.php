<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginInterface;


/**
 * @RestPlugin(
 *  id = "rest_plugin",
 *  label = @Translation("The plugin ID."),
 *  route = "api"
 * )
 */
class RestEndPointsPlugins implements RestPluginInterface {

  protected $callbacks = [
    'get' => 'get',
    'post' => 'post',
    'patch' => 'patch',
    'delete' => 'delete',
  ];

  /**
   * Gets the plugin_id of the plugin instance.
   *
   * @return string
   *   The plugin_id of the plugin instance.
   */
  public function getPluginId() {
  }

  /**
   * Gets the definition of the plugin implementation.
   *
   * @return array
   *   The plugin definition, as returned by the discovery object used by the
   *   plugin manager.
   */
  public function getPluginDefinition() {
  }
}
