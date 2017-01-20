<?php

namespace Drupal\bargain_core_test\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;

/**
 * Class BargainCoreTestExchangeController.
 *
 * @package Drupal\bargain_core_test\Controller
 */
class BargainCoreTestExchangeController extends ControllerBase {

  public function access() {
    return AccessResult::allowed();
  }

  /**
   * Dummydata.
   *
   * @return string
   *   Return Hello string.
   */
  public function dummyData() {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: dummyData'),
    ];
  }

}
