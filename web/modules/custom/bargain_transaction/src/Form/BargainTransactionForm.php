<?php

namespace Drupal\bargain_transaction\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Bargain transaction edit forms.
 *
 * @ingroup bargain_transaction
 */
class BargainTransactionForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\bargain_transaction\Entity\BargainTransaction */
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
        drupal_set_message($this->t('Created the %label Bargain transaction.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bargain transaction.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.bargain_transaction.canonical', ['bargain_transaction' => $entity->id()]);
  }

}
