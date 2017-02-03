<?php

namespace Drupal\Tests\bargain_core\Functional;

use Drupal\Tests\BrowserTestBase;
use Drupal\user\Entity\User;

/**
 * Base class for the rest tests.
 *
 * @group bargain
 */
abstract class AbstractRestPluginsTests extends BrowserTestBase {

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
   * Flag to know if the test required an access token application.
   *
   * @var bool
   */
  protected $setUpClient;

  /**
   * The path of the rest plugin.
   *
   * @var string
   */
  protected $requestCanonical = '/rest_user';

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $this->entityTypeManager = $this->container->get('entity_type.manager');
    $this->httpClient = $this->container->get('http_client');
    $this->json = $this->container->get('serialization.json');

    if ($this->setUpClient) {
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
  }

  /**
   * Get access token for the user.
   *
   * @param \Drupal\user\Entity\User $user
   *   The user object.
   *
   * @return string
   *   The access token which represent the user.
   */
  protected function createAccessTokenForUser(User $user) {
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
    return $response['access_token'];
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
    return $this->httpClient->request($request, $this->getAbsoluteUrl($this->requestCanonical), [
      'headers' => $headers,
      'form_params' => $body,
    ]);
  }

}
