<?php

namespace Drupal\bargain_chat\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bargain chat message entities.
 *
 * @ingroup bargain_chat
 */
interface BargainChatMessageInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bargain chat message name.
   *
   * @return string
   *   Name of the Bargain chat message.
   */
  public function getName();

  /**
   * Sets the Bargain chat message name.
   *
   * @param string $name
   *   The Bargain chat message name.
   *
   * @return \Drupal\bargain_chat\Entity\BargainChatMessageInterface
   *   The called Bargain chat message entity.
   */
  public function setName($name);

  /**
   * Gets the Bargain chat message creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bargain chat message.
   */
  public function getCreatedTime();

  /**
   * Sets the Bargain chat message creation timestamp.
   *
   * @param int $timestamp
   *   The Bargain chat message creation timestamp.
   *
   * @return \Drupal\bargain_chat\Entity\BargainChatMessageInterface
   *   The called Bargain chat message entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bargain chat message published status indicator.
   *
   * Unpublished Bargain chat message are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bargain chat message is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bargain chat message.
   *
   * @param bool $published
   *   TRUE to set this Bargain chat message to published, FALSE to set it to
   *   unpublished.
   *
   * @return \Drupal\bargain_chat\Entity\BargainChatMessageInterface
   *   The called Bargain chat message entity.
   */
  public function setPublished($published);

}
