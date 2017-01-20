<?php

namespace Drupal\Tests\bargain_core\Kernel;

use Drupal\Core\Config\InstallStorage;
use Drupal\Core\Url;
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
    'bargain_core_test',
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
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->installEntitySchema('bargain_coins');
    $this->installEntitySchema('exchange_rate');
    $this->installEntitySchema('user');
    $this->installConfig(['bargain_core']);

    $this->entityTypeManager = $this->container->get('entity_type.manager');

    $content = Url::fromRoute('bargain_core_test.exchange_dummy_data', [], ['absolute' => TRUE])->toString();

    $this->config('bargain_core.database')
      ->set('currency_source', $content)
      ->save();
  }

  /**
   * Testing pull exchange rate.
   */
  public function testPullExchange() {
  }

}
