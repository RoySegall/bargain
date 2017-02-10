<?php

namespace Drupal\bargain_chat;

use Drupal\bargain_core\BargainEntityStoragePusherTrait;
use Drupal\Core\Entity\Sql\SqlContentEntityStorage;

/**
 * Storage controller for the bargain chat room message entity.
 */
class BargainChatMessageStorage extends SqlContentEntityStorage {

  use BargainEntityStoragePusherTrait;

  /**
   * The channel the push event happens.
   *
   * @var string
   */
  protected $channel = 'chat_room_message';

}
