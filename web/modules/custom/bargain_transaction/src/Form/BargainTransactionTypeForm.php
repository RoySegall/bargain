<?php

namespace Drupal\bargain_transaction\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BargainTransactionTypeForm.
 *
 * @package Drupal\bargain_transaction\Form
 */
class BargainTransactionTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $bargain_transaction_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $bargain_transaction_type->label(),
      '#description' => $this->t("Label for the Bargain transaction type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $bargain_transaction_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\bargain_transaction\Entity\BargainTransactionType::load',
      ],
      '#disabled' => !$bargain_transaction_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $bargain_transaction_type = $this->entity;
    $status = $bargain_transaction_type->save();

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Bargain transaction type.', [
          '%label' => $bargain_transaction_type->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Bargain transaction type.', [
          '%label' => $bargain_transaction_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($bargain_transaction_type->toUrl('collection'));
  }

}
