<?php

namespace Drupal\bargain_exchange_rate_test;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Class BargainExchangeRateTestServiceProvider.
 */
class BargainExchangeRateTestServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    $container->getDefinition('bargain_exchange_rate.pull_exchange_rate')->setClass('Drupal\bargain_exchange_rate_test\BargainExchangeRateTestExchangeRatePull');
  }

}
