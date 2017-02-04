<?php

namespace Drupal\bargain_chat\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bargain chat room entities.
 *
 * @ingroup bargain_chat
 */
interface BargainChatRoomInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Bargain chat room name.
   *
   * @return string
   *   Name of the Bargain chat room.
   */
  public function getName();

  /**
   * Sets the Bargain chat room name.
   *
   * @param string $name
   *   The Bargain chat room name.
   *
   * @return \Drupal\bargain_chat\Entity\BargainChatRoomInterface
   *   The called Bargain chat room entity.
   */
  public function setName($name);

  /**
   * Gets the Bargain chat room creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bargain chat room.
   */
  public function getCreatedTime();

  /**
   * Sets the Bargain chat room creation timestamp.
   *
   * @param int $timestamp
   *   The Bargain chat room creation timestamp.
   *
   * @return \Drupal\bargain_chat\Entity\BargainChatRoomInterface
   *   The called Bargain chat room entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bargain chat room published status indicator.
   *
   * Unpublished Bargain chat room are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bargain chat room is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bargain chat room.
   *
   * @param bool $published
   *   TRUE to set this Bargain chat room to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bargain_chat\Entity\BargainChatRoomInterface
   *   The called Bargain chat room entity.
   */
  public function setPublished($published);

}
