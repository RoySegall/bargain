<?php

namespace Drupal\bargain_rest\Plugin;

use Drupal\bargain_core\BargainCoreEntityFlatten;
use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountProxy;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Entity\EntityTypeManagerInterface;

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
   * The arguments form the header.
   *
   * @var array
   */
  protected $arguments;

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
   * RestPluginBase constructor.
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
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, Request $request, RestPluginManager $plugin_manager, AccountProxy $account_proxy, EntityTypeManagerInterface $entity_manager, BargainCoreEntityFlatten $entity_flatten) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $request;
    $this->pluginManager = $plugin_manager;
    $this->accountProxy = $account_proxy;
    $this->entityTypeManager = $entity_manager;
    $this->entityFlatten = $entity_flatten;
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
      $container->get('bargain_core.entity_flatter')
    );
  }

  /**
   * Return the output of the callback.
   *
   * @return mixed
   */
  public function callback() {
    $request_type = strtolower($this->request->getMethod());

    if (empty($this->callbacks[$request_type])) {
      throw new NotFoundHttpException();
    }

    return new JsonResponse(call_user_func_array([$this, $this->callbacks[$request_type]], $this->arguments));
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

    $arguments = [];

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
        if ($entity = $this->entityTypeManager->getStorage($matches[1])->load($path_info[$key])) {
          $argument = $entity;
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

      $arguments[] = $argument;
    }

    $this->arguments = $arguments;

    return $this;
  }

}
