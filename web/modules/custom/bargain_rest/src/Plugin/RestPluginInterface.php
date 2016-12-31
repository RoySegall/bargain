<?php

namespace Drupal\bargain_rest\Plugin;
use Drupal\Core\Access\AccessResult;

/**
 * Defines an interface for Rest plugin plugins.
 */
interface RestPluginInterface {

  /**
   * Return access callback for the routes.
   *
   * @return AccessResult
   */
  public function access();

  /**
   * Return the output of the callback.
   *
   * @return mixed
   */
  public function callback();

}
