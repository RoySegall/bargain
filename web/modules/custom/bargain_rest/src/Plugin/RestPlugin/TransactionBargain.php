<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\bargain_transaction\Entity\BargainTransaction;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * TransactionBargain class.
 *
 * @RestPlugin(
 *  id = "transaction_bargain",
 *  path = "/transaction/{bargain_transaction}",
 *  label = @Translation("Transaction list"),
 *  description = @Translation("A single transaction"),
 *  entity_type = "bargain_transaction"
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

    $account = $this
      ->entityTypeManager
      ->getStorage('user')
      ->load($this->accountProxy->id());

    switch ($this->requestType) {
      case 'get':
        return AccessResult::allowedIf($this->entityTypeManager
          ->getAccessControlHandler('bargain_transaction')
          ->access($this->arguments[0], 'view', $account));
    }

    throw new BadRequestHttpException('This end point does not support the request type.');
  }

  /**
   * Get callback; Display the entity.
   *
   * @param BargainTransaction $bargain_transaction
   *   The bargain transaction entity.
   *
   * @return string
   */
  public function get(BargainTransaction $bargain_transaction) {
    return $this->entityFlatten->flatten($bargain_transaction);
  }

}
