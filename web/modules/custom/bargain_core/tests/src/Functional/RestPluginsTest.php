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
    $results = $this->json->decode($this->httpClient->request('get', $this->getAbsoluteUrl('/api'))->getBody()->getContents());

    $plugin_ids = array_keys($results);

    $this->assertTrue(in_array('transaction_bargain', $plugin_ids));
    $this->assertTrue(in_array('rest_user', $plugin_ids));
    $this->assertTrue(in_array('rest_plugin', $plugin_ids));
  }

}
