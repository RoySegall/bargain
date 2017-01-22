<?php

namespace Drupal\Tests\bargain_core\Functional;

use Drupal\Tests\BrowserTestBase;

/**
 * Testing Rest plugins.
 *
 * @group bargain
 */
class RestPluginsTest extends BrowserTestBase {

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

  /**
   * Testing the /api rest plugin.
   */
  public function testRestPlugins() {

//    $results = $this->json->decode($this->httpClient->get($this->baseUrl . '/api')->getBody()->getContents());

    $response = \Drupal::httpClient()->get($this->getAbsoluteUrl('/api'));


//    $this->assertTrue(key_exists('transaction_bargain', $results));
//    $this->assertTrue(key_exists('rest_user', $results));
//    $this->assertTrue(key_exists('rest_plugin', $results));
  }

}
