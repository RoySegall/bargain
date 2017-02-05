<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * TransactionBargainCreate class.
 *
 * @RestPlugin(
 *  id = "transaction_bargain_create",
 *  path = "/transaction",
 *  label = @Translation("Transaction list"),
 *  description = @Translation("A single transaction"),
 *  entity_type = "bargain_transaction"
 * )
 */
class TransactionBargainCreate extends RestPluginBase {

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
      // For some reason the tests failed if not loading the user account.
      $account = $this->getAccount();

      return AccessResult::allowedIf($this->entityTypeManager
        ->getAccessControlHandler('bargain_transaction')
        ->createAccess(NULL, $account));
    }

    throw new BadRequestHttpException('This end point does not support the request type.');
  }

}
