<?php

namespace Drupal\Tests\bargain_core\Functional;

use GuzzleHttp\Exception\ClientException;

/**
 * Testing the user end point.
 *
 * @group bargain
 */
class RestPluginsUserTest extends AbstractRestPlugins {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bargain_rest',
    'bargain_core',
    'simple_oauth',
    'text',
    'image',
  ];

  /**
   * The password of the client.
   *
   * @var string
   */
  protected $password;

  /**
   * The client entity.
   *
   * @var \Drupal\simple_oauth\Entity\Oauth2Client
   */
  protected $client;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();
    $this->password = $this->randomString();

    $this->client = $this->entityTypeManager->getStorage('oauth2_client')->create([
      'secret' => $this->password,
    ]);
    $this->client->save();
  }

  /**
   * Commit request helper function.
   *
   * @param array $headers
   *   The headers of the request.
   * @param array $body
   *   The body of the request.
   * @param string $request
   *   The request type.
   *
   * @return mixed|\Psr\Http\Message\ResponseInterface
   *   The response object.
   */
  protected function request(array $headers = [], array $body = [], $request = 'post') {
    return $this->httpClient->request('post', $this->getAbsoluteUrl('/rest_user'), [
      'headers' => $headers,
      'form_params' => $body,
    ]);
  }

  /**
   * Creating a user.
   */
  public function testRestPlugins() {
    // Trying to do failed requests.
    try {
      $this->request(['client_id' => 'foo']);
    }
    catch (ClientException $e) {
      $this->assertContains('There is no app with the app ID you provided.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request(['client_id' => $this->client->uuid(), 'client_secret' => 'bar']);
    }
    catch (ClientException $e) {
      $this->assertContains('The client password you provided is invalid.', $e->getResponse()->getBody()->getContents());
    }

    // The right app credentials.
    $client = [
      'client_id' => $this->client->uuid(),
      'client_secret' => $this->password,
    ];

    // Attempting an empty request.
    try {
      $this->request($client);
    }
    catch (ClientException $e) {
      $this->assertContains('You did not pass the next values: name, password, mail.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($client, ['name' => $this->randomString()]);
    }
    catch (ClientException $e) {
      $this->assertContains('You did not pass the next values: password, mail.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($client, [
        'name' => $this->randomString(),
        'password' => $this->randomString(),
      ]);
    }
    catch (ClientException $e) {
      $this->assertContains('You did not pass the next values: mail.', $e->getResponse()->getBody()->getContents());
    }

    // Creating a user.
    $user = [
      'name' => $this->randomString(),
      'password' => $this->randomString(),
      'mail' => 'foo@example.com',
    ];

    $body = $this->request($client, $user)->getBody()->getContents();

    $account = $this->json->decode($body);
    $this->assertEquals($user['name'], $account['name']);
    $this->assertEquals($user['mail'], $account['mail']);

    // Trying to create the same username.
    try {
      $this->request($client, [
        'name' => $user['name'],
        'mail' => $this->randomString(),
        'password' => $this->randomString(),
      ]);
    }
    catch (ClientException $e) {
      $this->assertContains('A user with that name already exists.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($client, [
        'name' => $this->randomString(),
        'mail' => $user['mail'],
        'password' => $this->randomString(),
      ]);
    }
    catch (ClientException $e) {
      $this->assertContains('A user with that mail already exists.', $e->getResponse()->getBody()->getContents());
    }

  }

}
