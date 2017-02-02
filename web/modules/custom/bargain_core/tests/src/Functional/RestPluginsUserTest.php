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
    'node',
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

    $path = $this->container->get('module_handler')->getModule('simple_oauth')->getPath();
    $public_key_path = DRUPAL_ROOT . '/' . $path . '/tests/certificates/public.key';
    $private_key_path = DRUPAL_ROOT . '/' . $path . '/tests/certificates/private.key';
    $settings = $this->config('simple_oauth.settings');
    $settings->set('public_key', $public_key_path);
    $settings->set('private_key', $private_key_path);
    $settings->save();

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
    return $this->httpClient->request($request, $this->getAbsoluteUrl('/rest_user'), [
      'headers' => $headers,
      'form_params' => $body,
    ]);
  }

  /**
   * Testing token creation and user validating.
   */
  public function testCreateToken() {
    $user = $this->drupalCreateUser();
    $user->setPassword(1234);
    $user->save();

    $request = $this->httpClient->request('post', $this->getAbsoluteUrl('/oauth/token'), [
      'form_params' => [
        'grant_type' => 'password',
        'client_id' => $this->client->uuid(),
        'client_secret' => $this->password,
        'username' => $user->label(),
        'password' => 1234,
      ],
    ]);

    $response = $this->json->decode($request->getBody()->getContents());
    $headers = ['Authorization' => 'Bearer ' . $response['access_token']];
    $user_info = $this->json->decode($this
      ->request($headers, [], 'get')
      ->getBody()
      ->getContents());

    $this->assertEquals($user_info['name'], $user->label());
    $this->assertEquals($user_info['mail'], $user->getEmail());
    $this->assertEquals($user_info['uid'], $user->id());
  }

  /**
   * Creating a user.
   */
  public function testUserCreate() {
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
      $this->assertContains('name: You must enter a username.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($client, ['name' => $this->randomMachineName()]);
    }
    catch (ClientException $e) {
      $this->assertContains('mail: Email field is required.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($client, [
        'name' => $this->randomMachineName(),
        'password' => $this->randomString(),
      ]);
    }
    catch (ClientException $e) {
      $this->assertContains('mail: Email field is required.', $e->getResponse()->getBody()->getContents());
    }

    // Creating a user.
    $user = [
      'name' => $this->randomMachineName(),
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
      $this->assertContains("The username &lt;em class=&quot;placeholder&quot;&gt;{$user['name']}&lt;/em&gt; is already taken.", $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($client, [
        'name' => $this->randomMachineName(),
        'mail' => $user['mail'],
        'password' => $this->randomString(),
      ]);
    }
    catch (ClientException $e) {
      $this->assertContains('The email address &lt;em class=&quot;placeholder&quot;&gt;foo@example.com&lt;/em&gt; is already taken.', $e->getResponse()->getBody()->getContents());
    }

  }

}
