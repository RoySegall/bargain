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
 *  path = "/transaction",
 *  label = @Translation("Transaction list"),
 *  description = @Translation("A single transaction")
 * )
 */
class TransactionBargain extends RestPluginBase {

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'post' => 'post',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {
    if ($this->requestType == 'post') {
      return AccessResult::allowedIf($this
        ->entityTypeManager
        ->getAccessControlHandler('bargain_transaction')
        ->createAccess());
    }

    return AccessResult::allowedIf($this
      ->entityTypeManager
      ->getAccessControlHandler('bargain_transaction')
      ->access($this->arguments[0], 'view'));
  }

  /**
   * Post handler;
   */
  protected function post() {
    // todo: create the bargain.
    return 'a';
  }

}
