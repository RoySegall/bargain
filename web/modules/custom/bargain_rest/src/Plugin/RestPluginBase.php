<?php

namespace Drupal\bargain_rest\Plugin;

use Drupal\Component\Plugin\PluginBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Base class for Rest plugin plugins.
 */
abstract class RestPluginBase extends PluginBase implements RestPluginInterface {

  protected $callbacks = [
    'get' => 'get',
    'post' => 'post',
    'patch' => 'patch',
    'delete' => 'delete',
  ];

  /**
   * Return the output of the callback.
   *
   * @return mixed
   */
  public function callback() {
    $request_type = strtolower(\Drupal::request()->getMethod());
//    return new JsonResponse($this->{$this->callbacks['get']}());
  }

}
