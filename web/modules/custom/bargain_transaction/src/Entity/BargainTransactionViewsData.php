<?php

namespace Drupal\bargain_transaction\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Bargain transaction entities.
 */
class BargainTransactionViewsData extends EntityViewsData {

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
