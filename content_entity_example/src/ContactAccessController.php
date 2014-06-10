<?php

/**
 * @file
 * Contains \Drupal\content_entity_example/ContactAccessController
 */

namespace Drupal\content_entity_example;

use Drupal\Core\Entity\EntityAccessController;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;

/**
 * Access controller for the comment entity.
 *
 * @see \Drupal\comment\Entity\Comment.
 */
class ContactAccessController extends EntityAccessController {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, $langcode, AccountInterface $account) {

    switch ($operation) {
      case 'view':
        return $account->hasPermission('view contact entity');
        break;

      case 'edit':
        return $account->hasPermission('edit contact entity');
        break;

      case 'delete':
        return $account->hasPermission('delete contact entity');
        break;

    }

    return TRUE;
  }

  /**
   * {@inheritdoc}
   */
  protected function checkCreateAccess(AccountInterface $account, array $context, $entity_bundle = NULL) {
    return $account->hasPermission('add contact entity');
  }
}


