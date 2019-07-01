<?php

namespace Drupal\survey;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for the Question entity entity.
 *
 * @see \Drupal\survey\Entity\QuestionEntity.
 */
class QuestionEntityAccessControlHandler extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\survey\Entity\QuestionEntityInterface $entity */
    switch ($operation) {
      case 'view':
        if (!$entity->isPublished()) {
          return AccessResult::allowedIfHasPermission($account, 'view unpublished question entity entities');
        }
        return AccessResult::allowedIfHasPermission($account, 'view published question entity entities');

      case 'update':
        return AccessResult::allowedIfHasPermission($account, 'edit question entity entities');

      case 'delete':
        return AccessResult::allowedIfHasPermission($account, 'delete question entity entities');
    }

    // Unknown operation, no opinion.
    return AccessResult::neutral();
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return AccessResult::allowedIfHasPermission($account, 'add question entity entities');
  }

}
