<?php

namespace Drupal\bargain_chat;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bargain chat message entity.
 *
 * @see \Drupal\bargain_chat\Entity\BargainChatMessage.
 */
class BargainChatMessageAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bargain_chat\Entity\BargainChatMessageInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bargain chat message entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bargain chat message entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bargain chat message entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bargain chat message entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bargain chat message entities');
  }

}
