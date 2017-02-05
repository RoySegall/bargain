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
   * @param array $entity_reference_handlers
   *   List of entity reference handlers. The array should be in the form of:
   *    [
   *      'field' => function($item) {
   *        return 'new_value';
   *      }
   *    ].
   *
   * @return array
   *   The entity in a JSON representation.
   */
  public function flatten(EntityInterface $entity, array $entity_reference_handlers = []) {
    $fields = $entity->toArray();
    $definitions = $entity->getFieldDefinitions();

    $return = [];
    foreach ($fields as $field => $value) {

      if ($entity->getEntityTypeId() == 'user' && $field == 'pass') {
        continue;
      }

      if (!empty($entity_reference_handlers[$field])) {
        $field_instance = $entity->get($field);

        if ($definitions[$field]->getType() == 'entity_reference') {
          $value = $entity_reference_handlers[$field]($field_instance->referencedEntities());
        }
        else {
          $value = $entity_reference_handlers[$field]($field_instance->value);

          if (!is_array($value)) {
            $value = [$value];
          }
        }
      }
      else {
        $value = array_map(function ($item) {
          return reset($item);
        }, $value);
      }

      $return[$field] = count($value) == 1 ? reset($value) : $value;
    }

    return array_filter($return);
  }

}
