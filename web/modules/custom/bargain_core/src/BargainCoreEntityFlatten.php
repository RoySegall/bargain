<?php

namespace Drupal\bargain_core;

use Drupal\Core\Entity\EntityInterface;

/**
 * Class BargainCorePush.
 */
class BargainCoreEntityFlatten {

  /**
   * Flattening an entity object to a JSON representation.
   *
   * @param EntityInterface $entity
   *   The entity object.
   *
   * @return array
   *   The entity in a JSON representation.
   */
  public function flatten(EntityInterface $entity) {
    $fields = $entity->toArray();

    $return = [];
    foreach ($fields as $field => $value) {

      if ($entity->getEntityTypeId() == 'user' && $field == 'pass') {
        continue;
      }

      $value = array_map(function ($item) {
        return reset($item);
      }, $value);

      $return[$field] = count($value) == 1 ? reset($value) : $value;
    }

    return array_filter($return);
  }

}
