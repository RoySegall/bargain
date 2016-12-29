<?php

/**
 * @file
 * Contains bargain_transaction.page.inc.
 *
 * Page callback for Bargain transaction entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bargain transaction templates.
 *
 * Default template: bargain_transaction.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bargain_transaction(array &$variables) {
  // Fetch BargainTransaction Entity Object.
  $bargain_transaction = $variables['elements']['#bargain_transaction'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
