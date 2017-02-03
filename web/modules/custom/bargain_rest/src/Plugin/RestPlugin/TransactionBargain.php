<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
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
    'get' => 'EntityGet',
    'patch' => 'EntityPatch',
    'delete' => 'EntityDelete',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {

    switch ($this->requestType) {
      case 'get':
        return $this->checkEntityAccess('view');

      case 'patch':
        return $this->checkEntityAccess('update');

      case 'delete':
        return $this->checkEntityAccess('delete');
    }

    throw new BadRequestHttpException('This end point does not support the request type.');
  }

}
