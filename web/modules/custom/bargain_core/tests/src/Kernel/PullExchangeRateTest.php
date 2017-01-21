<?php

namespace Drupal\Tests\bargain_core\Kernel;

use Drupal\Core\Cron;
use Drupal\KernelTests\KernelTestBase;

/**
 * Testing pull exchange rate.
 *
 * @group bargain
 */
class PullExchangeRateTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bargain_exchange_rate',
    'bargain_exchange_rate_test',
    'bargain_core',
    'user'
  ];

  /**
   * The entity type manager interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The config object service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * The cron service object.
   *
   * @var \Drupal\Core\Cron
   */
  protected $cron;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->installEntitySchema('bargain_coins');
    $this->installEntitySchema('exchange_rate');
    $this->installEntitySchema('user');
    $this->installConfig(['bargain_core']);

    $this->entityTypeManager = $this->container->get('entity_type.manager');
    $this->cron = $this->container->get('cron');
  }

  /**
   * Testing pull exchange rate.
   */
  public function testPullExchange() {
    $this->cron->run();
  }

}
