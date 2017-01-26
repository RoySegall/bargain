<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Password\PasswordInterface;

/**
 * RestUser class.
 *
 * @RestPlugin(
 *  id = "rest_user",
 *  path = "/rest_user",
 *  label = @Translation("Rest user"),
 *  description = @Translation("The user in the rest request")
 * )
 */
class RestUser extends RestPluginBase {

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'get' => 'get',
    'post' => 'post',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {
    if ($this->requestType == 'post') {
      // todo: set as plugin injection.
      $ids = \Drupal::entityQuery('oauth2_client')
        ->condition('uuid', $this->request->headers->get('client-id'))
        ->execute();

      if (!$ids) {
        return AccessResult::forbidden();
      }

      /** @var \Drupal\simple_oauth\Entity\Oauth2Client $client */
      $client = $this->entityTypeManager->getStorage('oauth2_client')->load(reset($ids));

      /** @var PasswordInterface $password_checker */
      $password_checker = \Drupal::service('password');
      AccessResult::allowedIf($password_checker->check($this->request->headers->get('client-secret'), $client->getSecret()));
    }

    return AccessResult::allowed();
  }

  /**
   * Get callback; Return list of plugins.
   */
  protected function get() {
    $account = $this->entityTypeManager->getStorage('user')->load($this->accountProxy->id());
    return $this->entityFlatten->flatten($account);
  }

  /**
   * Post callback; Create a user end point.
   */
  protected function post() {
    $this->request->request->all();

    // todo: Check if there's already a user with that name or mail.
    $user = $this->entityTypeManager->getStorage('user')->create($this->request->request->all());
    $user->save();
    return $this->entityFlatten->flatten($user);
  }

}
