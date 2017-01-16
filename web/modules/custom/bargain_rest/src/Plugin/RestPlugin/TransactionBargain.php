<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\bargain_transaction\Entity\BargainTransaction;
use Drupal\Core\Access\AccessResult;

/**
 * @RestPlugin(
 *  id = "rest_user",
 *  path = "/transaction/{bargain_transaction}",
 *  label = @Translation("Transaction list"),
 *  description = @Translation("A single transaction")
 * )
 */
class TransactionBargain extends RestPluginBase {

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

  /**
   * Get callback; Return list of plugins.
   *
   * @param \Drupal\bargain_transaction\Entity\BargainTransaction $transaction
   *   The entity object.
   *
   * @return mixed
   *   The page.
   */
  protected function get(BargainTransaction $transaction) {
    return $this->entityFlatten->flatten($transaction);
  }

}
