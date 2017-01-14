<?php

namespace Drupal\bargain_exchange_rate;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Exchange rate entities.
 *
 * @ingroup bargain_exchange_rate
 */
class ExchangeRateListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Exchange rate ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bargain_exchange_rate\Entity\ExchangeRate */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.exchange_rate.edit_form', array(
          'exchange_rate' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
