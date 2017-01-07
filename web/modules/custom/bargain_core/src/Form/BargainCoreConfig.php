<?php

namespace Drupal\bargain_core\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class BargainCoreConfig.
 */
class BargainCoreConfig extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'bargain_core.database',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'bargain_core_config';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('bargain_core.database');

    $form['app_id'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App ID'),
      '#description' => $this->t('Pusher app id'),
      '#default_value' => $config->get('app_id'),
    ];

    $form['app_key'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App key'),
      '#description' => $this->t('Pusher App key'),
      '#default_value' => $config->get('app_key'),
    ];

    $form['app_secret'] = [
      '#type' => 'textfield',
      '#title' => $this->t('App secret'),
      '#description' => $this->t('The app secret.'),
      '#default_value' => $config->get('app_secret'),
    ];

    $form = parent::buildForm($form, $form_state);

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);

    $this->config('bargain_core.database')
      ->set('app_id', $form_state->getValue('app_id'))
      ->set('app_key', $form_state->getValue('app_key'))
      ->set('app_secret', $form_state->getValue('app_secret'))
      ->save();
  }

}
