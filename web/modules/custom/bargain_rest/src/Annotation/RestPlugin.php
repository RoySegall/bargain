<?php

namespace Drupal\bargain_rest\Annotation;

use Drupal\Component\Annotation\Plugin;

/**
 * Defines a Rest plugin item annotation object.
 *
 * @see \Drupal\bargain_rest\Plugin\RestPluginManager
 * @see plugin_api
 *
 * @Annotation
 */
class RestPlugin extends Plugin {


  /**
   * The plugin ID.
   *
   * @var string
   */
  public $id;

  /**
   * The label of the plugin.
   *
   * @var \Drupal\Core\Annotation\Translation
   *
   * @ingroup plugin_translatable
   */
  public $label;

}
