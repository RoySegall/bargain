<?php

namespace Drupal\bargain_exchange_rate_test;

use Drupal\bargain_exchange_rate\BargainExchangeRatePull;

/**
 * Class BargainExchangeRateTestExchangeRatePull.
 */
class BargainExchangeRateTestExchangeRatePull extends BargainExchangeRatePull {

  /**
   * {@inheritdoc}
   */
  protected function getBody($source) {
    return file_get_contents(drupal_get_path('module', 'bargain_exchange_rate_test') . '/currency.xml');
  }

}
