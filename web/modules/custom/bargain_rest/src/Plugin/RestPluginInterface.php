<?php

namespace Drupal\bargain_rest\Plugin;

/**
 * Defines an interface for Rest plugin plugins.
 */
interface RestPluginInterface {

  /**
   * Return access callback for the routes.
   *
   * @return \Drupal\Core\Access\AccessResult
   *   Access result instance.
   */
  public function access();

  /**
   * Return the output of the callback.
   *
   * @return mixed
   *   Any value the routes will return.
   */
  public function callback();

}
