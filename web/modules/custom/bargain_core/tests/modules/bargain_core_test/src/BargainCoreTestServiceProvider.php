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
    $container->getDefinition('bargain_core.push')->setClass('Drupal\bargain_core_test\BargainCoreTestsPush');
  }

}
