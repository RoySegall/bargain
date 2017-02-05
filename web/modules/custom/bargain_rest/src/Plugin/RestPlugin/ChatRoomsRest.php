<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * ChatRoomsRest class.
 *
 * @RestPlugin(
 *  id = "chat_rooms_rest",
 *  path = "/messages",
 *  label = @Translation("Chat rooms"),
 *  description = @Translation("Displat list of the rooms which the user can access")
 * )
 */
class ChatRoomsRest extends RestPluginBase {

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

    switch ($this->requestType) {
      case 'get':
        return AccessResult::allowedIf($this->getRoomsForUsers(TRUE) > 0);
    }

    throw new BadRequestHttpException('This end point does not support the request type.');
  }

  /**
   * Get the list of rooms which the user can access to.
   *
   * @param bool $count
   *   Check if the query need to return a number or rooms or list of entities.
   *
   * @return string
   *   JSON which represent the list of rooms.
   */
  protected function getRoomsForUsers($count = FALSE) {
    $query = $this
      ->entityQuery
      ->get('bargain_chat_room', 'OR');

    if (!$this->getAccount()->hasPermission('administer bargain chat room entities')) {
      $query
        ->condition('user_id', $this->accountProxy->id())
        ->condition('buyer', $this->accountProxy->id());
    }

    if ($count) {
      return $query->count()->execute();
    }

    return $query->execute();
  }

  /**
   * Return list of queries.
   *
   * @return array
   *   List of entities.
   */
  public function entityQuery() {
    $rooms = $this->getRoomsForUsers();

    $entities = $this
      ->entityTypeManager
      ->getStorage('bargain_chat_room')
      ->loadMultiple($rooms);

    $return = [];

    foreach ($entities as $entity) {
      $return[] = $this->entityFlatten->flatten($entity, [
        'buyer' => function ($entities) {
          return $this->getReferencedUser($entities);
        },
        'user_id' => function ($entities) {
          return $this->getReferencedUser($entities);
        },
      ]);
    }

    return $return;
  }

  /**
   * Get the label and ID of referenced users from an entity reference field.
   *
   * @param \Drupal\user\Entity\User[] $entities
   *   List of items.
   *
   * @return array
   *   List of ids and labels.
   */
  protected function getReferencedUser(array $entities) {
    $return = [];

    foreach ($entities as $entity) {
      $return[] = ['id' => $entity->id(), 'label' => $entity->label()];
    }

    return $return;
  }

}
