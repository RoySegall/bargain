<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;

/**
 * ChatRoomMessagesRest class.
 *
 * @RestPlugin(
 *  id = "chat_room_message_rest",
 *  path = "/messages/{bargain_chat_room}",
 *  label = @Translation("Chat room message"),
 *  description = @Translation("Display all the messages in the current room.")
 * )
 */
class ChatRoomMessagesRest extends RestPluginBase {

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'get' => 'entityQuery',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {

    if ($this->getAccount()->hasPermission('administer bargain chat room entities')) {
      return AccessResult::allowed();
    }

    $entity = $this->arguments[0];
    return AccessResult::allowedIf(in_array($this->accountProxy->id(), [
      $entity->get('user_id')->referencedEntities()[0]->id(),
      $entity->get('buyer')->referencedEntities()[0]->id(),
    ]));
  }

  /**
   * Return list of entities.
   *
   * @return array
   *   List of entities.
   */
  public function entityQuery() {
  }

}
