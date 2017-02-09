<?php

namespace Drupal\Tests\bargain_core\Functional;

use GuzzleHttp\Exception\ClientException;

/**
 * Testing creation and reading messages.
 *
 * @group bargain
 */
class RestPluginsChatRoomMessagesTest extends AbstractRestPluginsTests {

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
  }

  /**
   * Adding messages to the room.
   */
  public function testChatRoomMessagesCreation() {
    // Create messages.
    $text = [$this->randomString(), $this->randomString()];
    $this->request($this->accessTokensHeaders['member1'], ['text' => $text[0]], 'post', $this->chatRoom->uuid());
    $this->request($this->accessTokensHeaders['member2'], ['text' => $text[1]], 'post', $this->chatRoom->uuid());

    // Check the content is OK.
    foreach (['member1', 'member2', 'admin'] as $member) {
      $messages = $this->request($this->accessTokensHeaders[$member], [], 'get', $this->chatRoom->uuid());
      $content = $this->json->decode($messages->getBody()->getContents());

      $this->assertEquals($content[0]['text'], $text[0]);
      $this->assertEquals($content[0]['user_id'], [
        'id' => $this->users['member1']->id(),
        'label' => $this->users['member1']->label(),
      ]);

      $this->assertEquals($content[1]['text'], $text[1]);
      $this->assertEquals($content[1]['user_id'], [
        'id' => $this->users['member2']->id(),
        'label' => $this->users['member2']->label(),
      ]);
    }

    // Access with the third user and check for not OK.
    try {
      $this->request($this->accessTokensHeaders['guest'], [], 'get', $this->chatRoom->uuid());
      $this->fail();
    }
    catch (ClientException $e) {
      $this->assertEquals($e->getResponse()->getStatusCode(), 403);
    }

    // Create another room and make sure there are no messages to display.
    $room = $this
      ->entityTypeManager
      ->getStorage('bargain_chat_room')
      ->create([
        'user_id' => $this->users['member1'],
        'buyer' => $this->users['guest'],
      ]);
    $room->save();

    foreach (['member1', 'guest', 'admin'] as $member) {
      $messages = $this->request($this->accessTokensHeaders[$member], [], 'get', $room->uuid());
      $content = $this->json->decode($messages->getBody()->getContents());
      $this->assertEmpty($content);
    }

    // Check access denied for member2.
    try {
      $this->request($this->accessTokensHeaders['member2'], [], 'get', $room->uuid());
      $this->fail();
    }
    catch (ClientException $e) {
      $this->assertEquals($e->getResponse()->getStatusCode(), 403);
    }
  }

  /**
   * Testing the update patch endpoint for changing the message status.
   */
  public function testChangeMessageStatus() {
    $text = [$this->randomString()];
    $this->request($this->accessTokensHeaders['member1'], ['text' => $text[0]], 'post', $this->chatRoom->uuid());

    // Check the message status changes to read.
    $messages = $this->request($this->accessTokensHeaders['member1'], [], 'get', $this->chatRoom->uuid());
    $content = $this->json->decode($messages->getBody()->getContents());

    $this->assertEquals($content[0]['status'], 'sent');

    // Changing the status.
    $this->request($this->accessTokensHeaders['member1'], [
      'status' => 'read',
      'message_uuid' => $content[0]['uuid'],
    ], 'patch', $this->chatRoom->uuid());

    $messages = $this->request($this->accessTokensHeaders['member1'], [], 'get', $this->chatRoom->uuid());
    $content = $this->json->decode($messages->getBody()->getContents());

    $this->assertEquals($content[0]['status'], 'read');

    // Check for message update with bad values.
    try {
      $this->request($this->accessTokensHeaders['member1'], [
        'status' => 'read',
        'message_uuid' => 'foo',
      ], 'patch', $this->chatRoom->uuid());
    }
    catch (ClientException $e) {
      $this->assertContains('There is no message which belong to this room or with the give uuid.', $e->getResponse()->getBody()->getContents());
    }

    try {
      $this->request($this->accessTokensHeaders['member1'], [
        'status' => 'foo',
        'message_uuid' => $content[0]['uuid'],
      ], 'patch', $this->chatRoom->uuid());
    }
    catch (ClientException $e) {
      $this->assertContains('The value you selected is not a valid choice.', $e->getResponse()->getBody()->getContents());
    }
  }

}
