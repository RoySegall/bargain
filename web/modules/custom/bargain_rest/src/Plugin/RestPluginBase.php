<?php

namespace Drupal\bargain_rest\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Base class for Rest plugin plugins.
 */
abstract class RestPluginBase extends PluginBase implements RestPluginInterface, ContainerFactoryPluginInterface {

  /**
   * The request object.
   *
   * @var \Symfony\Component\HttpFoundation\Request
   */
  protected $request;

  /**
   * The plugin manager instance.
   *
   * @var \Drupal\bargain_rest\Plugin\RestPluginManager
   */
  protected $pluginManager;

  /**
   * @var \Drupal\Core\Session\AccountProxy
   */
  protected $accountProxy;

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
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The request object.
   * @param RestPluginManager $plugin_manager
   *   The rest plugin manager.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, \Symfony\Component\HttpFoundation\Request $request, \Drupal\bargain_rest\Plugin\RestPluginManager $plugin_manager, \Drupal\Core\Session\AccountProxy $account_proxy) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->request = $request;
    $this->pluginManager = $plugin_manager;
    $this->accountProxy = $account_proxy;
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
      $container->get('current_user')
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

    return new JsonResponse($this->{$this->callbacks[$request_type]}());
  }

}
