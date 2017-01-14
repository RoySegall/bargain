<?php

namespace Drupal\bargain_exchange_rate\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Exchange rate entities.
 *
 * @ingroup bargain_exchange_rate
 */
interface ExchangeRateInterface extends  ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {

  // Add get/set methods for your configuration properties here.

  /**
   * Gets the Exchange rate name.
   *
   * @return string
   *   Name of the Exchange rate.
   */
  public function getName();

  /**
   * Sets the Exchange rate name.
   *
   * @param string $name
   *   The Exchange rate name.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\ExchangeRateInterface
   *   The called Exchange rate entity.
   */
  public function setName($name);

  /**
   * Gets the Exchange rate creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Exchange rate.
   */
  public function getCreatedTime();

  /**
   * Sets the Exchange rate creation timestamp.
   *
   * @param int $timestamp
   *   The Exchange rate creation timestamp.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\ExchangeRateInterface
   *   The called Exchange rate entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Exchange rate published status indicator.
   *
   * Unpublished Exchange rate are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Exchange rate is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Exchange rate.
   *
   * @param bool $published
   *   TRUE to set this Exchange rate to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\ExchangeRateInterface
   *   The called Exchange rate entity.
   */
  public function setPublished($published);

}
