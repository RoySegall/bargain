<?php

namespace Drupal\bargain_transaction;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Bargain transaction entities.
 *
 * @ingroup bargain_transaction
 */
class BargainTransactionListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Bargain transaction ID');
    $header['name'] = $this->t('Name');
    $header['type'] = $this->t('Type');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bargain_transaction\Entity\BargainTransaction */
    $row['id'] = $entity->id();
    $row['type'] = $entity->bundle();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.bargain_transaction.edit_form', array(
          'bargain_transaction' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
