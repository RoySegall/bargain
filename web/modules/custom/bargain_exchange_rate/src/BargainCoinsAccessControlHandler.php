<?php

namespace Drupal\bargain_exchange_rate;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bargain coins entity.
 *
 * @see \Drupal\bargain_exchange_rate\Entity\BargainCoins.
 */
class BargainCoinsAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bargain_exchange_rate\Entity\BargainCoinsInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bargain coins entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bargain coins entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bargain coins entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bargain coins entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bargain coins entities');
  }

}
