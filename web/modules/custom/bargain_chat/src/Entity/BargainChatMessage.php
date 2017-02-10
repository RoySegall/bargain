<?php

namespace Drupal\bargain_chat\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Bargain chat message entity.
 *
 * @ingroup bargain_chat
 *
 * @ContentEntityType(
 *   id = "bargain_chat_message",
 *   label = @Translation("Bargain chat message"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bargain_chat\BargainChatMessageListBuilder",
 *     "views_data" = "Drupal\bargain_chat\Entity\BargainChatMessageViewsData",
 *     "storage" = "Drupal\bargain_chat\BargainChatMessageStorage",
 *
 *     "form" = {
 *       "default" = "Drupal\bargain_chat\Form\BargainChatMessageForm",
 *       "add" = "Drupal\bargain_chat\Form\BargainChatMessageForm",
 *       "edit" = "Drupal\bargain_chat\Form\BargainChatMessageForm",
 *       "delete" = "Drupal\bargain_chat\Form\BargainChatMessageDeleteForm",
 *     },
 *     "access" = "Drupal\bargain_chat\BargainChatMessageAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bargain_chat\BargainChatMessageHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "bargain_chat_message",
 *   admin_permission = "administer bargain chat message entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/chat/bargain_chat_room/bargain_chat_message/{bargain_chat_message}",
 *     "add-form" = "/admin/content/chat/bargain_chat_room/bargain_chat_message/add",
 *     "edit-form" = "/admin/content/chat/bargain_chat_room/bargain_chat_message/{bargain_chat_message}/edit",
 *     "delete-form" = "/admin/content/chat/bargain_chat_room/bargain_chat_message/{bargain_chat_message}/delete",
 *     "collection" = "/admin/content/chat/bargain_chat_room/bargain_chat_message",
 *   },
 *   field_ui_base_route = "bargain_chat_message.settings"
 * )
 */
class BargainChatMessage extends ContentEntityBase implements BargainChatMessageInterface {

  use EntityChangedTrait;

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getName() {
    return $this->get('name')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setName($name) {
    $this->set('name', $name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? TRUE : FALSE);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields = parent::baseFieldDefinitions($entity_type);

    $fields['room'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Room'))
      ->setDescription(t('The room which the message belong to.'))
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setSetting('target_type', 'bargain_chat_room')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Bargain chat message entity.'))
      ->setRevisionable(TRUE)
      ->setRequired(TRUE)
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setTranslatable(TRUE)
      ->setDisplayOptions('view', array(
        'label' => 'hidden',
        'type' => 'author',
        'weight' => 0,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'weight' => 5,
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => '60',
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('list_string')
      ->setLabel(t('Publishing status'))
      ->setRequired(TRUE)
      ->setDescription(t('A boolean indicating whether the Bargain chat message is published.'))
      ->setDefaultValue(TRUE)
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
        'allowed_values' => [
          'sent' => 'Sent',
          'read' => 'Read',
        ],
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options',
        'weight' => 0,
      ));

    $fields['text'] = BaseFieldDefinition::create('text_long')
      ->setLabel(t('Text'))
      ->setRequired(TRUE)
      ->setDescription(t('The text of the message'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
      ))
      ->setDisplayOptions('form', array(
        'type' => 'text',
        'weight' => 0,
      ));

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }

}
