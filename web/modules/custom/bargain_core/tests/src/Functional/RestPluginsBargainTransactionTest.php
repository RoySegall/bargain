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
    $this->BargainTransactionCall('call');
  }

  /**
   * Creating a transaction call.
   */
  public function testBargainSeekCreate() {
    $this->BargainTransactionCall('seek');
  }

  /**
   * Creating different bargain transaction call.
   *
   * @param $bundle
   *   The type of the bargain - call, seek
   */
  protected function BargainTransactionCall($bundle) {
    // Make sure we can't do GET request.
    try {
      $this->request([], [], 'get');
    } catch (ClientException $e) {
      $this->assertContains('This end point does not support the request type', $e->getResponse()->getBody()->getContents());
    }

    // Try to create empty entity.
    try {
      $this->request($this->headers, ['type' => $bundle]);
    } catch (ClientException $e) {
      $result = $e->getResponse()->getBody()->getContents();
      $this->assertContains('coin: This value should not be null.', $result);
      $this->assertContains('amount: This value should not be null.', $result);
      $this->assertContains('exchange_rate: This value should not be null.', $result);
    }

    $result = $this->request($this->headers, [
      'type' => $bundle,
      'coin' => 'yen',
      'amount' => '100',
      'exchange_rate' => '15',
    ]);

    $new_entry = $this->json->decode($result->getBody()->getContents());

    // Accessing the page.
    $request_result = $this->request($this->headers, [], 'get', $new_entry['id']);
    $this->assertEquals($this->json->decode($request_result->getBody()->getContents()), $new_entry);

    try {
      $this->request([], [], 'get', $new_entry['id']);
      $this->fail();
    } catch (ClientException $e) {
      $this->assertTrue(TRUE);
    }

    // Updating the entity.
    $request_result = $this->request($this->headers, ['coin' => 'ILS'], 'patch', $new_entry['id']);
    $this->assertEquals($this->json->decode($request_result->getBody()->getContents())['coin'], 'ILS');

    try {
      $this->request([], ['coin' => 'ILS'], 'patch', $new_entry['id']);
      $this->fail();
    } catch (ClientException $e) {
      $this->assertTrue(TRUE);
    }

    // Delete the entity.
    try {
      $this->request([], [], 'delete', $new_entry['id']);
      $this->fail();
    } catch (ClientException $e) {
      $this->assertTrue(TRUE);
    }

    $this->request($this->headers, [], 'delete', $new_entry['id']);
    try {
      $this->request($this->headers, [], 'get', $new_entry['id']);
      $this->assertTrue(TRUE);
    } catch (ClientException $e) {
      $this->assertEquals($e->getResponse()->getStatusCode(), '404');
    }
  }

}
