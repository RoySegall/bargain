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
   * @var boolean
   */
  protected $setUpClient;

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
  protected function createAccessTokenForUser(\Drupal\user\Entity\User $user) {
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

}
