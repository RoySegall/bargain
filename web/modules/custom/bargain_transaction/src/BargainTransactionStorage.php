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
    BargainCore::getPush()->push('transactions', 'storage-' . $action, $this->entityToJson($entity));
    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function delete(array $entities) {
    $return = parent::delete($entities);

    foreach ($entities as $entity) {
      BargainCore::getPush()->push('transactions', 'storage-remove', $this->entityToJson($entity));
    }

    return $return;
  }

  /**
   * Convert a single entity to a JSON representation.
   *
   * @param EntityInterface $entity
   *   The entity object.
   *
   * @return array
   *   JSON representation of the entity.
   */
  public function entityToJson(EntityInterface $entity) {
    $object = [
      'id' => $entity->id(),
      'type' => $entity->bundle(),
    ] + BargainCore::getEntityFlatten()->flatten($entity);

    return $object;
  }

}
