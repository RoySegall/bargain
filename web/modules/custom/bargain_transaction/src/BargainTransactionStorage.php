<?php

namespace Drupal\bargain_transaction;

use Drupal\bargain_core\BargainEntityStoragePusherTrait;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Storage controller for the bargain transaction entity.
 */
class BargainTransactionStorage extends SqlContentEntityStorage {

  use BargainEntityStoragePusherTrait;

  /**
   * The channel the push event happens.
   *
   * @var string
   */
  protected $channel = 'transactions';

}
