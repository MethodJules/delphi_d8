<?php

namespace Drupal\survey\Entity;

use Drupal\Core\Config\Entity\ConfigEntityBundleBase;

/**
 * Defines the Question entity type entity.
 *
 * @ConfigEntityType(
 *   id = "question_entity_type",
 *   label = @Translation("Question entity type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\survey\QuestionEntityTypeListBuilder",
 *     "form" = {
 *       "add" = "Drupal\survey\Form\QuestionEntityTypeForm",
 *       "edit" = "Drupal\survey\Form\QuestionEntityTypeForm",
 *       "delete" = "Drupal\survey\Form\QuestionEntityTypeDeleteForm"
 *     },
 *     "route_provider" = {
 *       "html" = "Drupal\survey\QuestionEntityTypeHtmlRouteProvider",
 *     },
 *   },
 *   config_prefix = "question_entity_type",
 *   admin_permission = "administer site configuration",
 *   bundle_of = "question_entity",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/question_entity_type/{question_entity_type}",
 *     "add-form" = "/admin/structure/question_entity_type/add",
 *     "edit-form" = "/admin/structure/question_entity_type/{question_entity_type}/edit",
 *     "delete-form" = "/admin/structure/question_entity_type/{question_entity_type}/delete",
 *     "collection" = "/admin/structure/question_entity_type"
 *   }
 * )
 */
class QuestionEntityType extends ConfigEntityBundleBase implements QuestionEntityTypeInterface {

  /**
   * The Question entity type ID.
   *
   * @var string
   */
  protected $id;

  /**
   * The Question entity type label.
   *
   * @var string
   */
  protected $label;

}
