<?php

namespace Drupal\bargain_core_test;

use Drupal\Core\DependencyInjection\ContainerBuilder;
use Drupal\Core\DependencyInjection\ServiceProviderBase;

/**
 * Class BargainCoreTestServiceProvider.
 */
class BargainCoreTestServiceProvider extends ServiceProviderBase {

  /**
   * {@inheritdoc}
   */
  public function alter(ContainerBuilder $container) {
    if (\Drupal::hasService('bargain_core.push')) {
      $container->getDefinition('bargain_core.push')->setClass('Drupal\bargain_core_test\BargainCoreTestsPush');
    }

    if (\Drupal::hasService('bargain_exchange_rate.pull_exchange_rate')) {
      $container->getDefinition('bargain_exchange_rate.pull_exchange_rate')->setClass('Drupal\bargain_core_test\BargainCoreTestExchangeRatePull');
    }
  }

}
