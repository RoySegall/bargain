<?php

namespace Drupal\bargain_transaction;

use Drupal\bargain_core\BargainCore;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Storage controller for the bargain transaction entity.
 */
class BargainTransactionStorage extends SqlContentEntityStorage {

  /**
   * {@inheritdoc}
   */
  public function save(EntityInterface $entity) {
    $return = parent::save($entity);

    $action = $entity->isNew() ? 'added' : 'updated';

    BargainCore::getPush()->push('transactions', 'storage-' . $action, [
      'id' => $entity->id(),
    ]);

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function delete(array $entities) {
    $return = parent::delete($entities);

    foreach ($entities as $entity) {
      BargainCore::getPush()->push('transactions', 'storage-remove', [
        'id' => $entity->id(),
      ]);
    }

    return $return;
  }

}
