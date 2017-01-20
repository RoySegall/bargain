<?php

namespace Drupal\bargain_core;

use Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class BargainCorePush.
 */
class BargainCorePush implements BargainCorePushServiceInterface {

  /**
   * The app ID.
   *
   * @var mixed
   */
  protected $appId;

  /**
   * The app key.
   *
   * @var mixed
   */
  protected $appKey;

  /**
   * The app secret.
   *
   * @var mixed
   */
  protected $appSecret;

  /**
   * The pusher object.
   *
   * @var \Pusher
   */
  protected $pusher;

  /**
   * Constructor.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The factory for configuration objects.
   */
  public function __construct(ConfigFactoryInterface $config_factory) {
    $config = $config_factory->get('bargain_core.database');
    $this->appId = $config->get('app_id');
    $this->appKey = $config->get('app_key');
    $this->appSecret = $config->get('app_secret');

    $this->pusher = new \Pusher($this->appKey, $this->appSecret, $this->appId,
      ['encrypted' => TRUE]
    );
  }

  /**
   * {@inheritdoc}
   */
  public function push($channel, $event, $data) {
    $this->pusher->trigger($channel, $event, $data);
  }

}
