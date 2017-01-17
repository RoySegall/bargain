<?php

namespace Drupal\bargain_exchange_rate\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Bargain coins entities.
 */
class BargainCoinsViewsData extends EntityViewsData {

  /**
   * {@inheritdoc}
   */
  public function getViewsData() {
    $data = parent::getViewsData();

    // Additional information for Views integration, such as table joins, can be
    // put here.
    return $data;
  }

}
