<?php

namespace Drupal\Tests\bargain_core\Functional;

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
    'bargain_rest',
    'bargain_core',
    'bargain_chat',
    'simple_oauth',
    'text',
    'image',
    'options',
    'node',
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
  protected $accessTokens;

  /**
   * @var \Drupal\bargain_chat\Entity\BargainChatRoom;
   */
  protected $chatRoom;

  /**
   * {@inheritdoc}
   */
  public function setUp() {
    parent::setUp();

    $users = [
      'admin_user' => ['administer bargain chat room entities'],
      'member1' => ['view published bargain chat room entities'],
      'member2' => ['view published bargain chat room entities'],
      'guest' => [],
    ];

    foreach ($users as $user => $permissions) {
      $account = $this->createUser($permissions);
      $this->users[$user] = $account;
      $this->accessTokens[$user] = $this->createAccessTokenForUser($account);
    }

    $this->chatRoom = $this
      ->entityTypeManager
      ->getStorage('bargain_chat_room')
      ->create([
        'user_id' => $this->users['member1'],
        'buyer' => $this->users['member2'],
      ]);
    $this->chatRoom->save();
  }

  /**
   * Creating a transaction call.
   */
  public function testChatRoomMessages() {
    // Create a chat room with user 1 and 2.

    // Access with each of them and check for OK.

    // Access with the admin and check for OK.

    // Access with the third user and check for not OK.
  }

}
