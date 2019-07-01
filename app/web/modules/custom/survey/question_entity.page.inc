<?php

/**
 * @file
 * Contains question_entity.page.inc.
 *
 * Page callback for Question entity entities.
 */

use Drupal\Core\Render\Element;

/**
 * Prepares variables for Question entity templates.
 *
 * Default template: question_entity.html.twig.
 *
 * @param array $variables
 *   An associative array containing:
 *   - elements: An associative array containing the user information and any
 *   - attributes: HTML attributes for the containing element.
 */
function template_preprocess_question_entity(array &$variables) {
  // Fetch QuestionEntity Entity Object.
  $question_entity = $variables['elements']['#question_entity'];

  // Helpful $content variable for templates.
  foreach (Element::children($variables['elements']) as $key) {
    $variables['content'][$key] = $variables['elements'][$key];
  }
}
