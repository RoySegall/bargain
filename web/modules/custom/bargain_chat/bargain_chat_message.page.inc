<?php

/**
 * @file
 * Contains bargain_chat_message.page.inc.
 *
 * Page callback for Bargain chat message entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bargain chat message templates.
 *
 * Default template: bargain_chat_message.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bargain_chat_message(array &$variables) {
  // Fetch BargainChatMessage Entity Object.
  $bargain_chat_message = $variables['elements']['#bargain_chat_message'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
