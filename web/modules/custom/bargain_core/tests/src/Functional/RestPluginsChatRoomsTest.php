<?php

namespace Drupal\Tests\bargain_core\Functional;


/**
 * Testing the bargain transaction end points.
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
  protected $requestCanonical = '/messages';

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

    // Set up an admin user

    // Set up user chat room #1

    // Set up user chat room #2

    // Set up a user with access to see chat rooms.
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
