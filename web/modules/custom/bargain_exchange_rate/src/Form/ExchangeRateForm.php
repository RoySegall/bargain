<?php

namespace Drupal\bargain_exchange_rate\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Exchange rate edit forms.
 *
 * @ingroup bargain_exchange_rate
 */
class ExchangeRateForm extends ContentEntityForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\bargain_exchange_rate\Entity\ExchangeRate */
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
        drupal_set_message($this->t('Created the %label Exchange rate.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Exchange rate.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.exchange_rate.canonical', ['exchange_rate' => $entity->id()]);
  }

}
