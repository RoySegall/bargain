<?php

/**
 * @file
 * Contains bargain_coins.page.inc.
 *
 * Page callback for Bargain coins entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bargain coins templates.
 *
 * Default template: bargain_coins.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bargain_coins(array &$variables) {
  // Fetch BargainCoins Entity Object.
  $bargain_coins = $variables['elements']['#bargain_coins'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
