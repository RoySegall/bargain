<?php

namespace Drupal\Tests\bargain_core\Functional;

/**
 * Testing the bargains list end point.
 *
 * @group bargain
 */
class BargainEndPointTest extends AbstractRestPlugins {

  /**
   * {@inheritdoc}
   */
  public static $modules = [
    'bargain_rest',
    'bargain_core',
    'bargain_transaction',
  ];

  /**
   * Testing the bargains end point.
   */
  public function testBargainEndPointTest() {
    // Checking the transactions end point have info.
    $this->createBargain([
      'type' => 'seek',
      'coin' => 'ILS',
      'amount' => 1,
      'exchange_rate' => 30,
    ]);
    $this->createBargain([
      'type' => 'call',
      'coin' => 'euro',
      'amount' => 2,
      'exchange_rate' => 30,
    ]);

    $results = $this->json->decode($this->httpClient->request('get', $this->getAbsoluteUrl('/bargains/seek'))->getBody()->getContents())[0];
    $this->assertEquals($results['coin'], 'ILS');

    $results = $this->json->decode($this->httpClient->request('get', $this->getAbsoluteUrl('/bargains/call'))->getBody()->getContents())[0];
    $this->assertEquals($results['coin'], 'euro');
  }

  /**
   * Creating a bargain value.
   *
   * @param $values
   *   The bargain values.
   */
  protected function createBargain($values) {
    $this->entityTypeManager->getStorage('bargain_transaction')->create($values)->save();
  }

}
