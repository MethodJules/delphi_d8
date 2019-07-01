<?php

namespace Drupal\survey\Entity;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\RevisionLogInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\Core\Entity\EntityPublishedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Question entity entities.
 *
 * @ingroup survey
 */
interface QuestionEntityInterface extends ContentEntityInterface, RevisionLogInterface, EntityChangedInterface, EntityPublishedInterface, EntityOwnerInterface {

  /**
   * Add get/set methods for your configuration properties here.
   */

  /**
   * Gets the Question entity name.
   *
   * @return string
   *   Name of the Question entity.
   */
  public function getName();

  /**
   * Sets the Question entity name.
   *
   * @param string $name
   *   The Question entity name.
   *
   * @return \Drupal\survey\Entity\QuestionEntityInterface
   *   The called Question entity entity.
   */
  public function setName($name);

  /**
   * Gets the Question entity creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Question entity.
   */
  public function getCreatedTime();

  /**
   * Sets the Question entity creation timestamp.
   *
   * @param int $timestamp
   *   The Question entity creation timestamp.
   *
   * @return \Drupal\survey\Entity\QuestionEntityInterface
   *   The called Question entity entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Gets the Question entity revision creation timestamp.
   *
   * @return int
   *   The UNIX timestamp of when this revision was created.
   */
  public function getRevisionCreationTime();

  /**
   * Sets the Question entity revision creation timestamp.
   *
   * @param int $timestamp
   *   The UNIX timestamp of when this revision was created.
   *
   * @return \Drupal\survey\Entity\QuestionEntityInterface
   *   The called Question entity entity.
   */
  public function setRevisionCreationTime($timestamp);

  /**
   * Gets the Question entity revision author.
   *
   * @return \Drupal\user\UserInterface
   *   The user entity for the revision author.
   */
  public function getRevisionUser();

  /**
   * Sets the Question entity revision author.
   *
   * @param int $uid
   *   The user ID of the revision author.
   *
   * @return \Drupal\survey\Entity\QuestionEntityInterface
   *   The called Question entity entity.
   */
  public function setRevisionUserId($uid);

}
