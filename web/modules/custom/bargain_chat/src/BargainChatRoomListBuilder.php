<?php

namespace Drupal\bargain_chat;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;
use Drupal\Core\Routing\LinkGeneratorTrait;
use Drupal\Core\Url;

/**
 * Defines a class to build a listing of Bargain chat room entities.
 *
 * @ingroup bargain_chat
 */
class BargainChatRoomListBuilder extends EntityListBuilder {

  use LinkGeneratorTrait;

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = $this->t('Bargain chat room ID');
    $header['name'] = $this->t('Name');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\bargain_chat\Entity\BargainChatRoom */
    $row['id'] = $entity->id();
    $row['name'] = $this->l(
      $entity->label(),
      new Url(
        'entity.bargain_chat_room.edit_form', array(
          'bargain_chat_room' => $entity->id(),
        )
      )
    );
    return $row + parent::buildRow($entity);
  }

}
