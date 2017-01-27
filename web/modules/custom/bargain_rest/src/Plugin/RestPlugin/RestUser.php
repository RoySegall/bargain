<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Password\PasswordInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

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
        throw new BadRequestHttpException('There is no app with the app ID you provided.');
      }

      /** @var \Drupal\simple_oauth\Entity\Oauth2Client $client */
      $client = $this->entityTypeManager->getStorage('oauth2_client')->load(reset($ids));

      /** @var PasswordInterface $password_checker */
      $password_checker = \Drupal::service('password');
      if (!$password_checker->check($this->request->headers->get('client-secret'), $client->getSecret())) {
        throw new BadRequestHttpException('The client password you provided is invalid.');
      }
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
    $data = $this->request->request->all();

    /** @var \Drupal\Core\Entity\EntityStorageInterface $storage */
    $storage = $this->entityTypeManager->getStorage('user');

    if ($storage->loadByProperties(['name' => $data['name']])) {
      throw new BadRequestHttpException('A user with that name already exists.');
    }

    if ($storage->loadByProperties(['mail' => $data['mail']])) {
      throw new BadRequestHttpException('A user with that mail already exists.');
    }

    $user = $storage->create($data);
    $user->save();
    return $this->entityFlatten->flatten($user);
  }

}
