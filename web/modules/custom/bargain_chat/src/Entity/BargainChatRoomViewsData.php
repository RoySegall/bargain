<?php

namespace Drupal\bargain_chat\Entity;

use Drupal\views\EntityViewsData;

/**
 * Provides Views data for Bargain chat room entities.
 */
class BargainChatRoomViewsData extends EntityViewsData {

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
