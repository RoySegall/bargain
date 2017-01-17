<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;

/**
 * RestUser class.
 *
 * @RestPlugin(
 *  id = "rest_user",
 *  path = "/rest_user",
 *  label = @Translation("Rest user"),
 *  description = @Translation("The user in the rest request")
 * )
 */
class RestUser extends RestPluginBase {

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
    $account = $this->entityTypeManager->getStorage('user')->load($this->accountProxy->id());
    return $this->entityFlatten->flatten($account);
  }

}
