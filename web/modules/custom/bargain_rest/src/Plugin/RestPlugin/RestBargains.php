<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;

/**
 * RestBargains class.
 *
 * @RestPlugin(
 *  id = "rest_bargains",
 *  path = "/bargains/{type}",
 *  label = @Translation("Rest bargains"),
 *  description = @Translation("Display all the bargains."),
 *  entity_type = "bargain_transaction"
 * )
 */
class RestBargains extends RestPluginBase {

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'get' => 'entityQuery',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Return list of entities.
   *
   * @param string $type
   *   The bargain type.
   *
   * @return array
   *   List of entities.
   */
  public function entityQuery($type) {
    $results = $this
      ->entityQuery
      ->get('bargain_transaction')
      ->condition('type', $type)
      ->execute();

    if (!$results) {
      return [];
    }

    $bargains = $this
      ->entityTypeManager
      ->getStorage('bargain_transaction')
      ->loadMultiple($results);

    $return = [];
    foreach ($bargains as $bargain) {
      $return[] = $this->entityFlatten->flatten($bargain);
    }

    return $return;
  }

}
