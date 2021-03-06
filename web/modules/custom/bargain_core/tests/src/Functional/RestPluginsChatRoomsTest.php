<?php

namespace Drupal\Tests\bargain_core\Functional;

use GuzzleHttp\Exception\ClientException;

/**
 * Testing the chat rooms access.
 *
 * @group bargain
 */
class RestPluginsChatRoomsTest extends AbstractRestPluginsTests {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'text',
    'image',
    'options',
    'node',
    'bargain_rest',
    'bargain_core',
    'bargain_core_test',
    'bargain_chat',
    'simple_oauth',
  ];

  /**
   * {@inheritdoc}
   */
  protected $setUpClient = TRUE;

  /**
   * {@inheritdoc}
   */
  protected $requestCanonical = '/messages';

  /**
   * {@inheritdoc}
   */
  protected $profile = 'bargain';

  /**
   * The headers of the request including the access token.
   *
   * @var array
   */
  protected $headers;

  /**
   * List of users.
   *
   * @var \Drupal\user\Entity\User[]
   */
  protected $users;

  /**
   * List of access tokens.
   *
   * @var string[]
   */
  protected $accessTokensHeaders;

  /**
   * The chat room object.
   *
   * @var \Drupal\bargain_chat\Entity\BargainChatRoom
   */
  protected $chatRoom;

  /**
   * The config object service.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $configFactory;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $users = [
      'admin' => ['administer bargain chat room entities'],
      'member1' => ['view published bargain chat room entities'],
      'member2' => ['view published bargain chat room entities'],
      'guest' => [],
    ];

    foreach ($users as $user => $permissions) {
      $this->users[$user] = $this->createUser($permissions);
      $this->accessTokensHeaders[$user] = ['Authorization' => 'Bearer ' . $this->createAccessTokenForUser($this->users[$user])];
    }

    $this->chatRoom = $this
      ->entityTypeManager
      ->getStorage('bargain_chat_room')
      ->create([
        'user_id' => $this->users['member1'],
        'buyer' => $this->users['member2'],
      ]);
    $this->chatRoom->save();

    $this->configFactory = $this->container->get('config.factory');
  }

  /**
   * Trying to access the chat room.
   */
  public function testChatRoomMessages() {
    // Check push data.
    $config = $this->configFactory->get('bargain_core_test.database');

    $this->assertTrue($config->get('channel'), 'chat_room');
    $this->assertTrue($config->get('event'), 'storage-added');

    // Access with each of the user and check for OK results.
    foreach (['member1', 'member2', 'admin'] as $members) {
      $results = $this->request($this->accessTokensHeaders[$members], [], 'get');
      $content = $this->json->decode($results->getBody()->getContents());

      $this->assertEquals($content[0]['buyer'], ['id' => $this->users['member2']->id(), 'label' => $this->users['member2']->label()]);
      $this->assertEquals($content[0]['user_id'], ['id' => $this->users['member1']->id(), 'label' => $this->users['member1']->label()]);
    }

    // Access with the third user and check for not OK.
    try {
      $this->request($this->accessTokensHeaders['guest'], [], 'get');
      $this->fail();
    }
    catch (ClientException $e) {
      $this->assertEquals($e->getResponse()->getStatusCode(), 403);
    }
  }

}
