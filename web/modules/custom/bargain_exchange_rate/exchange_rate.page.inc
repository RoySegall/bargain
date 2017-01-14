<?php

/**
 * @file
 * Contains exchange_rate.page.inc.
 *
 * Page callback for Exchange rate entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Exchange rate templates.
 *
 * Default template: exchange_rate.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_exchange_rate(array &$variables) {
  // Fetch ExchangeRate Entity Object.
  $exchange_rate = $variables['elements']['#exchange_rate'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
