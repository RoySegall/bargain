<?php

namespace Drupal\bargain_exchange_rate;

use Drupal\Core\Entity\Query\QueryFactory;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\ConfigFactory;
use GuzzleHttp\Client;
use Symfony\Component\DomCrawler\Crawler;

/**
 * Class BargainTransactionPullExchangeRate.
 *
 * @package Drupal\bargain_transaction
 */
class BargainExchangeRatePull implements BargainExchangeRatePullInterface {

  /**
   * Drupal\Core\Entity\EntityManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Drupal\Core\Config\ConfigFactory definition.
   *
   * @var \Drupal\Core\Config\ConfigFactory
   */
  protected $configFactory;

  /**
   * GuzzleHttp\Client definition.
   *
   * @var \GuzzleHttp\Client
   */
  protected $httpClient;

  /**
   * The entity query for node.
   *
   * @var \Drupal\Core\Entity\Query\QueryInterface
   */
  protected $entityQuery;

  /**
   * Constructor.
   */
  public function __construct(EntityTypeManagerInterface $entity_manager, ConfigFactory $config_factory, Client $http_client, QueryFactory $query_factory) {
    $this->entityTypeManager = $entity_manager;
    $this->configFactory = $config_factory;
    $this->httpClient = $http_client;
    $this->entityQuery = $query_factory->get('exchange_rate');
  }

  /**
   * {@inheritdoc}
   */
  public function pull() {
    $source = $this->getSource();
    $request = $this->httpClient->get($source);
    $crawler = new Crawler($request->getBody(TRUE)->getContents());
    $date = strtotime($crawler->filterXPath('//CURRENCIES//LAST_UPDATE')->text());

    if ($exchange_rate = $this->exchangeRateExists($date)) {
      return $exchange_rate;
    }

    $coins = [];

    $crawler->filterXPath('//CURRENCIES//CURRENCY')->each(function (Crawler $node, $i) use (&$coins) {
      $node = $node->getNode(0);

      $properties = [];

      foreach (['NAME', 'UNIT', 'CURRENCYCODE', 'COUNTRY', 'RATE', 'CHANGE'] as $key) {
        $properties[strtolower($key)] = $node->getElementsByTagName($key)->item(0)->nodeValue;
      }

      $coin = $this->entityTypeManager->getStorage('bargain_coins')->create($properties);
      $coin->save();
      $coins[] = $coin->id();
    });

    return $this->createExchangeRate($date, $coins);
  }

  /**
   * Get the source of the exchange rate.
   *
   * @return string
   *   The source address.
   */
  protected function getSource() {
    return $this->configFactory->get('bargain_core.database')->get('currency_source');
  }

  /**
   * Create an exchange rate info from a bundle of coins.
   *
   * @param int $created
   *   The timestamp which the exchange rate last updated.
   * @param array $coins_ids
   *   List of coins entity ID or the entity object.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\ExchangeRate
   *   The exchange rate entity.
   */
  protected function createExchangeRate($created, array $coins_ids) {

    $entity = $this->entityTypeManager->getStorage('exchange_rate')->create([
      'created' => $created,
      'coins' => $coins_ids,
    ]);

    $entity->save();

    return $entity;
  }

  /**
   * Check if an exchange rate for the current date exists.
   *
   * @param int $created
   *   The timestamp which the exchange rate last updated.
   *
   * @return \Drupal\bargain_exchange_rate\Entity\ExchangeRate
   *   The exchange rate entity.
   */
  protected function exchangeRateExists($created) {
    $result = $this
      ->entityQuery
      ->condition('created', $created)
      ->execute();

    if ($result) {
      return $this->entityTypeManager->getStorage('exchange_rate')->load(reset($result));
    }

  }

}
