<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Drupal\user\Entity\User;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * TransactionBargain class.
 *
 * @RestPlugin(
 *  id = "transaction_bargain",
 *  path = "/transaction",
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
    'post' => 'entityCreate',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {
    if ($this->requestType == 'post') {
      $account = $this->entityTypeManager->getStorage('user')->load($this->accountProxy->id());
      return AccessResult::allowedIf($account->hasPermission('add bargain transaction entities'));
    }

    throw new BadRequestHttpException('This end point does not support the request type.');
  }

}
