<?php

namespace Drupal\bargain_transaction;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bargain transaction entity.
 *
 * @see \Drupal\bargain_transaction\Entity\BargainTransaction.
 */
class BargainTransactionAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bargain_transaction\Entity\BargainTransactionInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bargain transaction entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bargain transaction entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bargain transaction entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bargain transaction entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bargain transaction entities');
  }

}
