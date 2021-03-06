<?php

namespace Drupal\bargain_rest\Plugin;

use Drupal\bargain_core\BargainCoreEntityFlatten;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Access\AccessResult;
use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Entity\Query\QueryFactory;

/**
 * Base class for Rest plugin plugins.
 */
abstract class RestPluginBase extends PluginBase implements RestPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The request object.
   *
   * @var Request
   */
  protected $request;

  /**
   * The plugin manager instance.
   *
   * @var RestPluginManager
   */
  protected $pluginManager;

  /**
   * The account proxy instance.
   *
   * @var AccountProxy
   */
  protected $accountProxy;

  /**
   * Drupal\Core\Entity\EntityManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Entity flatten service.
   *
   * @var \Drupal\bargain_core\BargainCoreEntityFlatten
   */
  protected $entityFlatten;

  /**
   * Entity query service.
   *
   * @var \Drupal\Core\Entity\Query\QueryFactory
   */
  protected $entityQuery;

  /**
   * The arguments form the header.
   *
   * @var array
   */
  protected $arguments = [];

  /**
   * Payload of the request.
   *
   * @var array
   */
  protected $payload;

  /**
   * List of callbacks.
   *
   * @var array
   */
  protected $callbacks = [
    'get' => 'get',
    'post' => 'post',
    'patch' => 'patch',
    'delete' => 'delete',
  ];

  /**
   * The rest request type. i.e: get, post.
   *
   * @var string
   */
  protected $requestType;

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
    QueryFactory $query_factory
  ) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $request;
    $this->pluginManager = $plugin_manager;
    $this->accountProxy = $account_proxy;
    $this->entityTypeManager = $entity_manager;
    $this->entityFlatten = $entity_flatten;
    $this->entityQuery = $query_factory;
    $this->requestType = strtolower($this->request->getMethod());
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
      $container->get('entity.query')
    );
  }

  /**
   * Get the available methods.
   *
   * @return string[]
   *   List of the available methods.
   */
  public function getMethods() {
    return array_keys($this->callbacks);
  }

  /**
   * {@inheritdoc}
   */
  public function callback() {
    if (empty($this->callbacks[$this->requestType])) {
      throw new NotFoundHttpException();
    }

    $request = $this->request->request;
    $this->payload = $request->all();
    return new JsonResponse(call_user_func_array([$this, $this->callbacks[$this->requestType]], $this->arguments));
  }

  /**
   * Setting the arguments of the handler.
   */
  public function setArguments() {
    if ($this->arguments) {
      return $this;
    }

    $path_info = explode('/', $this->request->getPathInfo());
    $plugin_path = explode('/', $this->pluginDefinition['path']);

    // Collecting the arguments.
    foreach ($plugin_path as $key => $info) {
      if (empty($info)) {
        continue;
      }

      if (!preg_match('/{(.*)}/', $info, $matches)) {
        // This is no an argument format. Skipping.
        continue;
      }

      $entity_types = $this->entityTypeManager->getDefinitions();

      if (in_array($matches[1], array_keys($entity_types))) {
        // This is an argument representing entity type. Check if we can load
        // it from the DB.
        if ($entity = $this->entityTypeManager->getStorage($matches[1])->loadByProperties(['uuid' => $path_info[$key]])) {
          $argument = reset($entity);
        }
        else {
          // Nope. the value in the argument isn't a valid entity ID. Throwing
          // 404.
          throw new NotFoundHttpException();
        }
      }
      else {
        // Simple argument. Chain it to the array of arguments.
        $argument = $path_info[$key];
      }

      $this->arguments[] = $argument;
    }

    return $this;
  }

  /**
   * Validate the entity object before saving.
   *
   * @param ContentEntityInterface $entity
   *   The entity object.
   *
   * @throws \Symfony\Component\HttpKernel\Exception\BadRequestHttpException
   */
  protected function entityValidate(ContentEntityInterface $entity) {
    /** @var \Drupal\Core\Entity\EntityConstraintViolationList $errors */
    $errors = $entity->validate();

    if (count($errors)) {
      $request_error = [];

      foreach ($errors as $item) {
        $request_error[] = $item->getPropertyPath() . ': ' . $item->getMessage();
      }

      if ($request_error) {
        throw new BadRequestHttpException(implode("\n", $request_error));
      }
    }
  }

  /**
   * Create an entity from payload.
   */
  protected function entityCreate() {
    $entity = $this->entityTypeManager->getStorage($this->pluginDefinition['entity_type'])->create($this->payload);
    $this->entityValidate($entity);
    $entity->save();
    return $this->entityFlatten->flatten($entity);
  }

  /**
   * Check the entity access.
   *
   * @param string $operation
   *   The type of the operation: create, view, update, delete.
   *
   * @return AccessResult
   *   The access result class.
   */
  protected function checkEntityAccess($operation) {
    $account = $this->entityTypeManager->getStorage('user')->load($this->accountProxy->id());
    return AccessResult::allowedIf($this->entityTypeManager
      ->getAccessControlHandler($this->pluginDefinition['entity_type'])
      ->access($this->arguments[0], $operation, $account));
  }

  /**
   * Display the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   *
   * @return string
   *   The JSON representation of the entity.
   */
  protected function entityGet(EntityInterface $entity) {
    return $this->entityFlatten->flatten($entity);
  }

  /**
   * Updating the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   *
   * @return string
   *   The JSON representation of the entity.
   */
  protected function entityPatch(EntityInterface $entity) {
    foreach ($this->payload as $key => $value) {
      $entity->set($key, $value);
    }

    $this->entityValidate($entity);
    $entity->save();

    return $this->entityFlatten->flatten($entity);
  }

  /**
   * Delete the entity.
   *
   * @param \Drupal\Core\Entity\EntityInterface $entity
   *   The entity object.
   */
  protected function entityDelete(EntityInterface $entity) {
    $entity->delete();
  }

  /**
   * Load the account.
   *
   * @return \Drupal\user\Entity\User
   *   The user object.
   */
  protected function getAccount() {
    return $this
      ->entityTypeManager
      ->getStorage('user')
      ->load($this->accountProxy->id());
  }

  /**
   * Get the label and ID of referenced users from an entity reference field.
   *
   * @param \Drupal\user\Entity\User[] $entities
   *   List of items.
   *
   * @return array
   *   List of ids and labels.
   */
  protected function getReferencedUser(array $entities) {
    $return = [];

    foreach ($entities as $entity) {
      $return[] = ['id' => $entity->id(), 'label' => $entity->label()];
    }

    return $return;
  }

}
