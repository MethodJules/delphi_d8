<?php

namespace Drupal\survey;

use Drupal\Core\Entity\ContentEntityStorageInterface;
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
interface QuestionEntityStorageInterface extends ContentEntityStorageInterface {

  /**
   * Gets a list of Question entity revision IDs for a specific Question entity.
   *
   * @param \Drupal\survey\Entity\QuestionEntityInterface $entity
   *   The Question entity entity.
   *
   * @return int[]
   *   Question entity revision IDs (in ascending order).
   */
  public function revisionIds(QuestionEntityInterface $entity);

  /**
   * Gets a list of revision IDs having a given user as Question entity author.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   The user entity.
   *
   * @return int[]
   *   Question entity revision IDs (in ascending order).
   */
  public function userRevisionIds(AccountInterface $account);

  /**
   * Counts the number of revisions in the default language.
   *
   * @param \Drupal\survey\Entity\QuestionEntityInterface $entity
   *   The Question entity entity.
   *
   * @return int
   *   The number of revisions in the default language.
   */
  public function countDefaultLanguageRevisions(QuestionEntityInterface $entity);

  /**
   * Unsets the language for all Question entity with the given language.
   *
   * @param \Drupal\Core\Language\LanguageInterface $language
   *   The language object.
   */
  public function clearRevisionsLanguage(LanguageInterface $language);

}
