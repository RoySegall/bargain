<?php

namespace Drupal\Tests\bargain_core\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Testing Rest plugins.
 *
 * @group bargain
 */
class AbstractRestPlugins extends BrowserTestBase {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bargain_rest',
    'bargain_core',
  ];

  /**
   * The entity type manager interface.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The HTTP client service.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The JSON service.
   *
   * @var \Drupal\Component\Serialization\Json
   */
  protected $json;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->entityTypeManager = $this->container->get('entity_type.manager');
    $this->httpClient = $this->container->get('http_client');
    $this->json = $this->container->get('serialization.json');
  }

}
