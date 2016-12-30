<?php

namespace Drupal\bargain_transaction\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Bargain transaction type entity.
 *
 * @ConfigEntityType(
 *   id = "bargain_transaction_type",
 *   label = @Translation("Bargain transaction type"),
 *   handlers = {
 *     "list_builder" = "Drupal\bargain_transaction\BargainTransactionTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\bargain_transaction\Form\BargainTransactionTypeForm",
 *       "edit" = "Drupal\bargain_transaction\Form\BargainTransactionTypeForm",
 *       "delete" = "Drupal\bargain_transaction\Form\BargainTransactionTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\bargain_transaction\BargainTransactionTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "bargain_transaction_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "bargain_transaction",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/bargain_transaction_type/{bargain_transaction_type}",
 *     "add-form" = "/admin/structure/bargain_transaction_type/add",
 *     "edit-form" = "/admin/structure/bargain_transaction_type/{bargain_transaction_type}/edit",
 *     "delete-form" = "/admin/structure/bargain_transaction_type/{bargain_transaction_type}/delete",
 *     "collection" = "/admin/structure/bargain_transaction_type"
 *   }
 * )
 */
class BargainTransactionType extends ConfigEntityBundleBase implements BargainTransactionTypeInterface {

  /**
   * The Bargain transaction type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Bargain transaction type label.
   *
   * @var string
   */
  protected $label;

}
