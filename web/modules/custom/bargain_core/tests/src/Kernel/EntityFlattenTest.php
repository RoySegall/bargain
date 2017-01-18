<?php

namespace Drupal\Tests\bargain_core\Kernel;

use Drupal\KernelTests\KernelTestBase;

/**
 * Tests generation of ice cream.
 *
 * @group bargain
 */
class EntityFlattenTest extends KernelTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = ['bargain_core'];

  /**
   * Checking the entity flatten service.
   */
  public function testEntityFlatten() {
    $this->assertEquals(1, 2);
  }

}
