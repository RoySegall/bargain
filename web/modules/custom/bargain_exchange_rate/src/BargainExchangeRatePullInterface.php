<?php

namespace Drupal\bargain_exchange_rate;

/**
 * Interface BargainExchangeRatePullInterface.
 *
 * @package Drupal\bargain_exchange_rate
 */
interface BargainExchangeRatePullInterface {

  /**
   * Pull the data from the exchange rate.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\ExchangeRate
   *   The exchange rate entity.
   */
  public function pull();

}
