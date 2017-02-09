<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_chat\Entity\BargainChatRoom;
use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\EntityInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * ChatRoomMessagesRest class.
 *
 * @RestPlugin(
 *  id = "chat_room_message_rest",
 *  path = "/messages/{bargain_chat_room}",
 *  label = @Translation("Chat room message"),
 *  description = @Translation("Display all the messages in the current room."),
 *  entity_type = "bargain_chat_message"
 * )
 */
class ChatRoomMessagesRest extends RestPluginBase {

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'get' => 'entityQuery',
    'post' => 'entityCreate',
    'patch' => 'messagePatch',
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
   * @param \Drupal\bargain_chat\Entity\BargainChatRoom $entity
   *   The room object.
   *
   * @return array
   *   List of entities.
   */
  public function entityQuery(BargainChatRoom $entity) {
    $results = $this
      ->entityQuery
      ->get('bargain_chat_message')
      ->condition('room', $entity->id())
      ->execute();

    if (!$results) {
      return;
    }

    $messages = $this
      ->entityTypeManager
      ->getStorage('bargain_chat_message')
      ->loadMultiple($results);

    $return = [];
    foreach ($messages as $message) {
      $return[] = $this->entityFlatten->flatten($message, [
        'user_id' => function ($entities) {
          return $this->getReferencedUser($entities);
        },
      ]);
    }

    return $return;
  }

  /**
   * {@inheritdoc}
   */
  public function entityCreate() {
    $this->payload['room'] = $this->arguments[0]->id();
    $this->payload['status'] = 'sent';
    return parent::entityCreate();
  }

  /**
   * Change the status property of the message.
   */
  public function messagePatch() {
    $message = $this->entityTypeManager->getStorage('bargain_chat_message')->loadByProperties([
      'uuid' => $this->payload['message_uuid'],
      'room' => $this->arguments[0]->id(),
    ]);

    if (!$message) {
      throw new BadRequestHttpException('There is no message which belong to this room or with the give uuid.');
    }

    /** @var EntityInterface $entity */
    $entity = reset($message);
    $entity->set('status', $this->payload['status']);
    $entity->save();

    $this->entityValidate($entity);
    $entity->save();

    return $this->entityFlatten->flatten($entity);
  }

}
