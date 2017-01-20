<?php

namespace Drupal\bargain_core_test;

use Drupal\bargain_core\BargainCorePushServiceInterface;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\DependencyInjection\ContainerInjectionInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class BargainCorePush.
 */
class BargainCoreTestsPush implements BargainCorePushServiceInterface, ContainerInjectionInterface {

  /**
   * @var \Drupal\Core\Config\ImmutableConfig
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory')
    );
  }

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $this->configFactory = $config_factory->getEditable('bargain_core_test.database');
  }

  /**
   * {@inheritdoc}
   */
  public function push($channel, $event, $data) {
    $this->configFactory->set('channel', $data);
    $this->configFactory->set('event', $data);
    $this->configFactory->set('data', $data);
  }

}
