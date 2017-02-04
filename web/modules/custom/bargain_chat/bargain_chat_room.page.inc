<?php

/**
 * @file
 * Contains bargain_chat_room.page.inc.
 *
 * Page callback for Bargain chat room entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Bargain chat room templates.
 *
 * Default template: bargain_chat_room.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_bargain_chat_room(array &$variables) {
  // Fetch BargainChatRoom Entity Object.
  $bargain_chat_room = $variables['elements']['#bargain_chat_room'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
