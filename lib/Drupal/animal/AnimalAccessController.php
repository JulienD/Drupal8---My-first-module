<?php

/**
 * @file
 * Contains \Drupal\animal\AnimalAccessController
 */

namespace Drupal\animal;

use Drupal\Core\Entity\EntityAccessController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the comment entity.
 *
 * @see \Drupal\animal\Entity\Comment.
 */
class AnimalAccessController extends EntityAccessController {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {
    switch ($operation) {
      case 'view':
      case 'add':
        return user_access('access animal', $account);
        break;

      case 'update':
        return ($account->id() && $account->id() == $entity->uid->value && $entity->status->value == COMMENT_PUBLISHED && user_access('edit own animal', $account)) || user_access('administer animals', $account);
        break;

      case 'delete':
        return user_access('administer animals', $account);
        break;

      case 'approve':
        return user_access('administer animals', $account);
        break;
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return user_access('create animals', $account);
  }

}
