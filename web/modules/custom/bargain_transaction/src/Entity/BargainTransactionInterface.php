<?php

namespace Drupal\bargain_transaction\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Bargain transaction entities.
 *
 * @ingroup bargain_transaction
 */
interface BargainTransactionInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  /**
   * Gets the Bargain transaction type.
   *
   * @return string
   *   The Bargain transaction type.
   */
  public function getType();

  /**
   * Gets the Bargain transaction name.
   *
   * @return string
   *   Name of the Bargain transaction.
   */
  public function getName();

  /**
   * Sets the Bargain transaction name.
   *
   * @param string $name
   *   The Bargain transaction name.
   *
   * @return \Drupal\bargain_transaction\Entity\BargainTransactionInterface
   *   The called Bargain transaction entity.
   */
  public function setName($name);

  /**
   * Gets the Bargain transaction creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Bargain transaction.
   */
  public function getCreatedTime();

  /**
   * Sets the Bargain transaction creation timestamp.
   *
   * @param int $timestamp
   *   The Bargain transaction creation timestamp.
   *
   * @return \Drupal\bargain_transaction\Entity\BargainTransactionInterface
   *   The called Bargain transaction entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Bargain transaction published status indicator.
   *
   * Unpublished Bargain transaction are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Bargain transaction is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Bargain transaction.
   *
   * @param bool $published
   *   TRUE to set this Bargain transaction to published, FALSE to set it to
   *   unpublished.
   *
   * @return \Drupal\bargain_transaction\Entity\BargainTransactionInterface
   *   The called Bargain transaction entity.
   */
  public function setPublished($published);

}
