<?php

namespace Drupal\bargain_exchange_rate\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bargain coins entities.
 *
 * @ingroup bargain_exchange_rate
 */
interface BargainCoinsInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bargain coins name.
   *
   * @return string
   *   Name of the Bargain coins.
   */
  public function getName();

  /**
   * Sets the Bargain coins name.
   *
   * @param string $name
   *   The Bargain coins name.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\BargainCoinsInterface
   *   The called Bargain coins entity.
   */
  public function setName($name);

  /**
   * Gets the Bargain coins creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bargain coins.
   */
  public function getCreatedTime();

  /**
   * Sets the Bargain coins creation timestamp.
   *
   * @param int $timestamp
   *   The Bargain coins creation timestamp.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\BargainCoinsInterface
   *   The called Bargain coins entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bargain coins published status indicator.
   *
   * Unpublished Bargain coins are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bargain coins is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bargain coins.
   *
   * @param bool $published
   *   TRUE to set this Bargain coins to published, FALSE to set it to
   *   unpublished.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\BargainCoinsInterface
   *   The called Bargain coins entity.
   */
  public function setPublished($published);

}
