<?php

namespace Drupal\bargain_chat\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Bargain chat room edit forms.
 *
 * @ingroup bargain_chat
 */
class BargainChatRoomForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\bargain_chat\Entity\BargainChatRoom */
    $form = parent::buildForm($form, $form_state);

    $entity = $this->entity;

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = &$this->entity;

    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Bargain chat room.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bargain chat room.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.bargain_chat_room.canonical', ['bargain_chat_room' => $entity->id()]);
  }

}
