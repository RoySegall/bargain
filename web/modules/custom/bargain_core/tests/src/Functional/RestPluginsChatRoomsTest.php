<?php

namespace Drupal\Tests\bargain_core\Functional;

use GuzzleHttp\Exception\ClientException;

/**
 * Testing the bargain transaction end points.
 *
 * @group bargain
 */
class RestPluginsBargainTransactionTest extends AbstractRestPluginsTests {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bargain_rest',
    'bargain_core',
    'bargain_transaction',
    'simple_oauth',
    'text',
    'image',
    'node',
  ];

  /**
   * {@inheritdoc}
   */
  protected $setUpClient = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $requestCanonical = '/transaction';

  /**
   * The headers of the request including the access token.
   *
   * @var array
   */
  protected $headers;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $user = $this->drupalCreateUser([
      'add bargain transaction entities',
      'view published bargain transaction entities',
      'edit bargain transaction entities',
      'delete bargain transaction entities',
    ]);
    $this->headers = ['Authorization' => 'Bearer ' . $this->createAccessTokenForUser($user)];
  }

  /**
   * Creating a transaction call.
   */
  public function testBargainCallCreate() {
    $this->bargainTransactionCall('call');
  }

  /**
   * Creating a transaction call.
   */
  public function testBargainSeekCreate() {
    $this->bargainTransactionCall('seek');
  }

  /**
   * Creating different bargain transaction call.
   *
   * @param string $bundle
   *   The type of the bargain - call, seek.
   */
  protected function bargainTransactionCall($bundle) {

  }

}
