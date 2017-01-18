<?php

namespace Drupal\Tests\bargain_core\Unit;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Tests\UnitTestCase;

/**
 * Tests generation of ice cream.
 *
 * @group bargain
 */
class EntityFlattenTest extends UnitTestCase {

  /**
   * @var \Drupal\Core\Entity\EntityInterface|\Prophecy\Prophecy\ObjectProphecy
   */
  protected $entity;

  function setUp() {
    parent::setUp();

    $this->entity = $this->prophesize(EntityInterface::class);
  }

  /**
   * Checking the entity flatten service.
   */
  public function testEntityFlatten() {
    $this->entity->id()->willReturn(0);
  }

}
