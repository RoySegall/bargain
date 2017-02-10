<?php

namespace Drupal\bargain_core;

use Drupal\Core\Cache\CacheBackendInterface;
use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityInterface;

/**
 * Sharing push notification logic between storage controllers.
 */
trait BargainEntityStoragePusherTrait {

  /**
   * The push service object.
   *
   * @var \Drupal\bargain_core\BargainCorePushServiceInterface
   */
  protected $bargainPush;

  /**
   * The entity flatten service.
   *
   * @var \Drupal\bargain_core\BargainCoreEntityFlatten
   */
  protected $entityFlatten;

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, EntityTypeInterface $entity_type) {
    return new static(
      $entity_type,
      $container->get('database'),
      $container->get('entity.manager'),
      $container->get('cache.entity'),
      $container->get('language_manager'),
      $container->get('bargain_core.push'),
      $container->get('bargain_core.entity_flatter')
    );
  }

  /**
   * BargainTransactionStorage constructor.
   *
   * @param \Drupal\Core\Entity\EntityTypeInterface $entity_type
   *   The entity type definition.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection to be used.
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   * @param \Drupal\Core\Cache\CacheBackendInterface $cache
   *   The cache backend to be used.
   * @param \Drupal\Core\Language\LanguageManagerInterface $language_manager
   *   The language manager.
   * @param \Drupal\bargain_core\BargainCorePushServiceInterface $bargain_push
   *   The bargain push service.
   * @param \Drupal\bargain_core\BargainCoreEntityFlatten $entity_flatten
   *   The entity flatten service.
   */
  public function __construct(
    EntityTypeInterface $entity_type,
    Connection $database,
    EntityManagerInterface $entity_manager,
    CacheBackendInterface $cache,
    LanguageManagerInterface $language_manager,
    BargainCorePushServiceInterface $bargain_push,
    BargainCoreEntityFlatten $entity_flatten
  ) {
    parent::__construct($entity_type, $database, $entity_manager, $cache, $language_manager);
    $this->bargainPush = $bargain_push;
    $this->entityFlatten = $entity_flatten;
  }

  /**
   * {@inheritdoc}
   */
  public function save(EntityInterface $entity) {
    $action = $entity->isNew() ? 'added' : 'updated';
    $return = parent::save($entity);
    $this->bargainPush->push($this->channel, 'storage-' . $action, $this->entityToJson($entity));
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
    ] + $this->entityFlatten->flatten($entity);

    return $object;
  }

}
