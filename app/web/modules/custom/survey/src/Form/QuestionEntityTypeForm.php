<?php

namespace Drupal\survey\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Class QuestionEntityTypeForm.
 */
class QuestionEntityTypeForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);

    $question_entity_type = $this->entity;
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Label'),
      '#maxlength' => 255,
      '#default_value' => $question_entity_type->label(),
      '#description' => $this->t("Label for the Question entity type."),
      '#required' => TRUE,
    ];

    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $question_entity_type->id(),
      '#machine_name' => [
        'exists' => '\Drupal\survey\Entity\QuestionEntityType::load',
      ],
      '#disabled' => !$question_entity_type->isNew(),
    ];

    /* You will need additional form elements for your custom properties. */

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $question_entity_type = $this->entity;
    $status = $question_entity_type->save();

    switch ($status) {
      case SAVED_NEW:
        $this->messenger()->addMessage($this->t('Created the %label Question entity type.', [
          '%label' => $question_entity_type->label(),
        ]));
        break;

      default:
        $this->messenger()->addMessage($this->t('Saved the %label Question entity type.', [
          '%label' => $question_entity_type->label(),
        ]));
    }
    $form_state->setRedirectUrl($question_entity_type->toUrl('collection'));
  }

}
