<?php

namespace Drupal\bargain_chat;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Bargain chat room entity.
 *
 * @see \Drupal\bargain_chat\Entity\BargainChatRoom.
 */
class BargainChatRoomAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\bargain_chat\Entity\BargainChatRoomInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished bargain chat room entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published bargain chat room entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit bargain chat room entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete bargain chat room entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add bargain chat room entities');
  }

}
