<?php

namespace Drupal\bargain_exchange_rate;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Exchange rate entity.
 *
 * @see \Drupal\bargain_exchange_rate\Entity\ExchangeRate.
 */
class ExchangeRateAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bargain_exchange_rate\Entity\ExchangeRateInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished exchange rate entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published exchange rate entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit exchange rate entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete exchange rate entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add exchange rate entities');
  }

}
