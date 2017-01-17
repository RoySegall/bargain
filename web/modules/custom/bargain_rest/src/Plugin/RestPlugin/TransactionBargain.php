<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\bargain_transaction\Entity\BargainTransaction;
use Drupal\Core\Access\AccessResult;

/**
 * TransactionBargain class.
 *
 * @RestPlugin(
 *  id = "transaction_bargain",
 *  path = "/transaction/{bargain_transaction}",
 *  label = @Translation("Transaction list"),
 *  description = @Translation("A single transaction")
 * )
 */
class TransactionBargain extends RestPluginBase {

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
    /** @var \Drupal\bargain_transaction\Entity\BargainTransaction $bargain_transaction */
    $bargain_transaction = $this->arguments[0];

    return AccessResult::allowedIf($bargain_transaction->access('view'));
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
