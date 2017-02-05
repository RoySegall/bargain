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
    'bargain_core_test',
    'bargain_transaction',
    'user',
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
   * The values of the transaction.
   *
   * @var array
   */
  protected $transactionValues;

  /**
   * The bargain transaction entity.
   *
   * @var \Drupal\bargain_transaction\Entity\BargainTransaction
   */
  protected $transaction;

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

    $this->transactionValues = [
      'name' => $this->randomString(),
      'coin' => $this->randomString(),
      'amount' => 20,
      'exchange_rate' => 130,
      'type' => 'call',
    ];

    $this->transaction = $this
      ->entityTypeManager
      ->getStorage('bargain_transaction')
      ->create($this->transactionValues);
    $this->transaction->save();
  }

  /**
   * Checking the entity flatten service.
   */
  public function testEntityFlatten() {
    $flatten = $this->entityFlatten->flatten($this->transaction);

    foreach ($this->transactionValues as $property => $value) {
      $this->assertEquals($flatten[$property], $value);
    }

    // Verify the event pushing information.
    $config = $this->configFactory->get('bargain_core_test.database');
    $data = unserialize($config->get('data'));

    $this->assertTrue($config->get('channel'), 'transactions');
    $this->assertTrue($config->get('event'), 'storage-added');
    $this->assertEquals($flatten, $data);
  }

  /**
   * Testing the transformer argument of the entit flatten oject.
   */
  public function testEntityFlattenTransformer() {
    $flatten = $this->entityFlatten->flatten($this->transaction, [
      'langcode' => function ($item) {
        return $item . '_foo';
      },
      'type' => function ($item) {
        return $item[0]->id() . '_foo';
      },
    ]);
    $this->assertEquals($flatten['langcode'], 'en_foo');
    $this->assertEquals($flatten['type'], 'call_foo');
  }

}
