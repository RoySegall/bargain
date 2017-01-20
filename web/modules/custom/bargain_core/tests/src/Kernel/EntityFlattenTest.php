<?php

namespace Drupal\Tests\bargain_core\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Check entity flatten service.
 *
 * @group bargain
 */
class EntityFlattenTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bargain_core',
    'bargain_transaction',
    'user',
    'bargain_core_test',
  ];

  /**
   * The entity type manager interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The entity flatten service.
   *
   * @var \Drupal\bargain_core\BargainCoreEntityFlatten
   */
  protected $entityFlatten;

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

    $this->installEntitySchema('bargain_transaction');
    $this->installEntitySchema('user');
    $this->installConfig('bargain_transaction');

    $this->entityTypeManager = $this->container->get('entity_type.manager');
    $this->entityFlatten = $this->container->get('bargain_core.entity_flatter');
    $this->configFactory = $this->container->get('config.factory');
  }

  /**
   * Checking the entity flatten service.
   */
  public function testEntityFlatten() {
    $values = [
      'name' => $this->randomString(),
      'coin' => $this->randomString(),
      'amount' => 20,
      'exchange_rate' => 130,
      'type' => 'call',
    ];

    $transaction = $this
      ->entityTypeManager
      ->getStorage('bargain_transaction')
      ->create($values);
    $transaction->save();
    $flatten = $this->entityFlatten->flatten($transaction);

    foreach ($values as $property => $value) {
      $this->assertEquals($flatten[$property], $value);
    }

    // Verify the event pushing information.
    $config = $this->configFactory->get('bargain_core_test.database');
    $data = unserialize($config->get('data'));

    $this->assertTrue($config->get('channel'), 'transactions');
    $this->assertTrue($config->get('event'), 'storage-added');
    $this->assertEquals($flatten, $data);
  }

}
