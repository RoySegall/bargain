<?php

namespace Drupal\bargain_core_test;

use Drupal\bargain_exchange_rate\BargainExchangeRatePull;
use Drupal\Core\Url;

/**
 * Class BargainCoreTestExchangeRatePull.
 */
class BargainCoreTestExchangeRatePull extends BargainExchangeRatePull {

  /**
   * {@inheritdoc}
   */
  protected function getSource() {
    return Url::fromRoute('bargain_core_test.exchange_dummy_data', [], ['absolute' => TRUE])->toString();
  }

}
