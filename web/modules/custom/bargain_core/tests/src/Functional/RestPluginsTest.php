<?php

namespace Drupal\Tests\bargain_core\Functional;

/**
 * Testing Rest plugins.
 *
 * @group bargain
 */
class RestPluginsTestsTest extends AbstractRestPluginsTests {

  /**
   * Testing the /api rest plugin.
   */
  public function testRestPlugins() {
    $results = $this->json->decode($this->httpClient->request('get', $this->getAbsoluteUrl('/api'))->getBody()->getContents());

    $plugin_ids = array_keys($results);

    $this->assertTrue(in_array('chat_rooms_rest', $plugin_ids));
    $this->assertTrue(in_array('chat_room_message_rest', $plugin_ids));
    $this->assertTrue(in_array('transaction_bargain', $plugin_ids));
    $this->assertTrue(in_array('transaction_bargain_create', $plugin_ids));
    $this->assertTrue(in_array('rest_user', $plugin_ids));
    $this->assertTrue(in_array('rest_plugin', $plugin_ids));
  }

}
