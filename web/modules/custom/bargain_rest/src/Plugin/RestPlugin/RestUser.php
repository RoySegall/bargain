<?php

namespace Drupal\bargain_rest\Plugin\RestPlugin;

use Drupal\bargain_rest\Plugin\RestPluginBase;
use Drupal\bargain_rest\Plugin\RestPluginManager;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Password\PasswordInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Drupal\bargain_core\BargainCoreEntityFlatten;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * RestUser class.
 *
 * @RestPlugin(
 *  id = "rest_user",
 *  path = "/rest_user",
 *  label = @Translation("Rest user"),
 *  description = @Translation("The user in the rest request"),
 *  entity_type = "user"
 * )
 */
class RestUser extends RestPluginBase {

  /**
   * The password checker service.
   *
   * @var \Drupal\Core\Password\PasswordInterface
   */
  protected $PasswordChecker;

  /**
   * RestPluginBase constructor.
   *
   * @param array $configuration
   *   A configuration array containing information about the plugin instance.
   * @param string $plugin_id
   *   The plugin_id for the plugin instance.
   * @param mixed $plugin_definition
   *   The plugin implementation definition.
   * @param Request $request
   *   The request object.
   * @param RestPluginManager $plugin_manager
   *   The rest plugin manager.
   * @param \Drupal\Core\Session\AccountProxy $account_proxy
   *   The account proxy.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_manager
   *   The entity type manager.
   * @param \Drupal\bargain_core\BargainCoreEntityFlatten $entity_flatten
   *   The entity flatten service.
   * @param \Drupal\Core\Entity\Query\QueryFactory $query_factory
   *   The query factory service.
   * @param \Drupal\Core\Password\PasswordInterface $password_checker
   *   The password checker service.
   */
  public function __construct(
    array $configuration,
    $plugin_id,
    $plugin_definition,
    Request $request,
    RestPluginManager $plugin_manager,
    AccountProxy $account_proxy,
    EntityTypeManagerInterface $entity_manager,
    BargainCoreEntityFlatten $entity_flatten,
    QueryFactory $query_factory,
    PasswordInterface $password_checker
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition, $request, $plugin_manager, $account_proxy, $entity_manager, $entity_flatten, $query_factory);
    $this->PasswordChecker = $password_checker;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
      $configuration,
      $plugin_id,
      $plugin_definition,
      $container->get('request_stack')->getCurrentRequest(),
      $container->get('plugin.manager.rest_plugin'),
      $container->get('current_user'),
      $container->get('entity_type.manager'),
      $container->get('bargain_core.entity_flatter'),
      $container->get('entity.query'),
      $container->get('password')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected $callbacks = [
    'get' => 'get',
    'post' => 'entityCreate',
    'patch' => 'patch',
  ];

  /**
   * {@inheritdoc}
   */
  public function access() {
    if ($this->requestType == 'post') {
      $ids = $this->entityQuery->get('oauth2_client')
        ->condition('uuid', $this->request->headers->get('client-id'))
        ->execute();

      if (!$ids) {
        throw new BadRequestHttpException('There is no app with the app ID you provided.');
      }

      /** @var \Drupal\simple_oauth\Entity\Oauth2Client $client */
      $client = $this->entityTypeManager->getStorage('oauth2_client')->load(reset($ids));

      /** @var PasswordInterface $password_checker */
      if (!$this->PasswordChecker->check($this->request->headers->get('client-secret'), $client->getSecret())) {
        throw new BadRequestHttpException('The client password you provided is invalid.');
      }
    }

    return AccessResult::allowed();
  }

  /**
   * Get callback; Return list of plugins.
   */
  public function get() {
    $account = $this->entityTypeManager->getStorage('user')->load($this->accountProxy->id());
    return $this->entityFlatten->flatten($account);
  }

  /**
   * Patching the user entity.
   */
  public function patch() {
    $account = $this->getAccount();

    if (!empty($this->payload['pass'])) {

      // Check the old pass exists.
      if (empty($this->payload['previous_pass'])) {
        throw new BadRequestHttpException('You did not provide the previous password.');
      }

      // Check if the the previous pass match with the current pass.
      if (!$this->PasswordChecker->check($this->payload['previous_pass'], $account->getPassword())) {
        throw new BadRequestHttpException('The client password you provided does not matching to the current password.');
      }

      unset($this->payload['previous_pass']);

      // For some reason the password constrain mess up the password update.
      // Skip on that and verify it by our self. No time for other stuff.
      $account->_skipProtectedUserFieldConstraint = TRUE;
    }

    $this->entityPatch($account);
  }

}
