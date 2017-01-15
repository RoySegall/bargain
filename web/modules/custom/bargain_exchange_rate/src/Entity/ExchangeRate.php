<?php

namespace Drupal\bargain_exchange_rate\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Exchange rate entity.
 *
 * @ingroup bargain_exchange_rate
 *
 * @ContentEntityType(
 *   id = "exchange_rate",
 *   label = @Translation("Exchange rate"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\bargain_exchange_rate\ExchangeRateListBuilder",
 *     "views_data" = "Drupal\bargain_exchange_rate\Entity\ExchangeRateViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\bargain_exchange_rate\Form\ExchangeRateForm",
 *       "add" = "Drupal\bargain_exchange_rate\Form\ExchangeRateForm",
 *       "edit" = "Drupal\bargain_exchange_rate\Form\ExchangeRateForm",
 *       "delete" = "Drupal\bargain_exchange_rate\Form\ExchangeRateDeleteForm",
 *     },
 *     "access" = "Drupal\bargain_exchange_rate\ExchangeRateAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\bargain_exchange_rate\ExchangeRateHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "exchange_rate",
 *   admin_permission = "administer exchange rate entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *     "langcode" = "langcode",
 *     "status" = "status",
 *   },
 *   links = {
 *     "canonical" = "/admin/content/exchange_rate/{exchange_rate}",
 *     "add-form" = "/admin/content/exchange_rate/add",
 *     "edit-form" = "/admin/content/exchange_rate/{exchange_rate}/edit",
 *     "delete-form" = "/admin/content/exchange_rate/{exchange_rate}/delete",
 *     "collection" = "/admin/content/exchange_rate",
 *   },
 *   field_ui_base_route = "exchange_rate.settings"
 * )
 */
class ExchangeRate extends ContentEntityBase implements ExchangeRateInterface {

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

    $fields['user_id'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Authored by'))
      ->setDescription(t('The user ID of author of the Exchange rate entity.'))
      ->setRevisionable(TRUE)
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

    $fields['coins'] = BaseFieldDefinition::create('entity_reference')
      ->setLabel(t('Coins'))
      ->setDescription(t('A list of coins which represent the exchange rate.'))
      ->setCardinality(BaseFieldDefinition::CARDINALITY_UNLIMITED)
      ->setRevisionable(TRUE)
      ->setSetting('target_type', 'bargain_coins')
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

    $fields['name'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Exchange rate entity.'))
      ->setSettings(array(
        'max_length' => 50,
        'text_processing' => 0,
      ))
      ->setDefaultValue('')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string_textfield',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['status'] = BaseFieldDefinition::create('boolean')
      ->setLabel(t('Publishing status'))
      ->setDescription(t('A boolean indicating whether the Exchange rate is published.'))
      ->setDefaultValue(TRUE);

    $fields['created'] = BaseFieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    return $fields;
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageInterface $storage) {
    $this->setName('Exchange rate form date ' . \Drupal::service('date.formatter')->format($this->getCreatedTime(), '', 'd/m/Y'));
    parent::preSave($storage);
  }

  /**
   * {@inheritdoc}
   */
  public function delete() {
    $ids = array_map(function ($key) {
      return $key['target_id'];
    }, $this->coins->getValue());

    $storage = $this->entityTypeManager()->getStorage('bargain_coins');

    $storage->delete($storage->loadMultiple($ids));
    parent::delete();
  }

}
