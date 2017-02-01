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

    throw new BadRequestHttpException('This end point does not support the request type.');
  }

  /**
   * Post handler; Saving the entry.
   */
  protected function post() {
    $entity = $this->entityTypeManager->getStorage('bargain_transaction')->create($this->payload);
    $this->entityValidate($entity);
    $entity->save();
    return $this->entityFlatten->flatten($entity);
  }

}
