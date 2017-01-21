<?php

namespace Drupal\Tests\bargain_core\Kernel;

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
    'user',
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

    /** @var \Drupal\bargain_exchange_rate\Entity\BargainCoins[] $coins */
    $coins = $this->entityTypeManager->getStorage('bargain_coins')->loadMultiple();

    // Verify we got the right amount of coins.
    $this->assertEquals(count($coins), 14);

    $expected_coins = [
      'USD' => '3.811',
      'GBP' => '4.6824',
      'JPY' => '3.3106',
      'EUR' => '4.0563',
      'AUD' => '2.8668',
      'CAD' => '2.8557',
      'DKK' => '0.5455',
      'NOK' => '0.4510',
      'ZAR' => '0.2806',
      'SEK' => '0.4268',
      'CHF' => '3.7821',
      'JOD' => '5.3725',
      'LBP' => '0.0253',
      'EGP' => '0.2016',
    ];

    $coins_id = [];
    foreach ($coins as $coin) {
      $coins_id[] = $coin->id();
      $this->assertEquals($expected_coins[$coin->get('currencycode')->value], $coin->get('rate')->value);
    }

    // Load the exchange bag.
    /** @var \Drupal\bargain_exchange_rate\Entity\ExchangeRate $bag */
    $bag = $this->entityTypeManager->getStorage('exchange_rate')->load(1);

    // Check we have the right timestamp.
    $this->assertEquals($bag->get('created')->value, strtotime('2017-01-20'));

    // Verify the coins in the bag are the coins we got before.
    foreach ($bag->get('coins')->referencedEntities() as $entity) {
      $this->assertTrue(in_array($entity->id(), $coins_id));
    }
  }

}
