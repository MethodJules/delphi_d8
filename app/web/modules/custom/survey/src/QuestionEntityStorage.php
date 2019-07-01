<?php

namespace Drupal\survey;

use Drupal\Core\Entity\Sql\SqlContentEntityStorage;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Language\LanguageInterface;
use Drupal\survey\Entity\QuestionEntityInterface;

/**
 * Defines the storage handler class for Question entity entities.
 *
 * This extends the base storage class, adding required special handling for
 * Question entity entities.
 *
 * @ingroup survey
 */
class QuestionEntityStorage extends SqlContentEntityStorage implements QuestionEntityStorageInterface {

  /**
   * {@inheritdoc}
   */
  public function revisionIds(QuestionEntityInterface $entity) {
    return $this->database->query(
      'SELECT vid FROM {question_entity_revision} WHERE id=:id ORDER BY vid',
      [':id' => $entity->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function userRevisionIds(AccountInterface $account) {
    return $this->database->query(
      'SELECT vid FROM {question_entity_field_revision} WHERE uid = :uid ORDER BY vid',
      [':uid' => $account->id()]
    )->fetchCol();
  }

  /**
   * {@inheritdoc}
   */
  public function countDefaultLanguageRevisions(QuestionEntityInterface $entity) {
    return $this->database->query('SELECT COUNT(*) FROM {question_entity_field_revision} WHERE id = :id AND default_langcode = 1', [':id' => $entity->id()])
      ->fetchField();
  }

  /**
   * {@inheritdoc}
   */
  public function clearRevisionsLanguage(LanguageInterface $language) {
    return $this->database->update('question_entity_revision')
      ->fields(['langcode' => LanguageInterface::LANGCODE_NOT_SPECIFIED])
      ->condition('langcode', $language->getId())
      ->execute();
  }

}
