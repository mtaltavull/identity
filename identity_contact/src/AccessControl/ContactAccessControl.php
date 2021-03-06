<?php

/**
 * @file
 * Contains \Drupal\identity_contact\AccessControl\ContactAccessControl.
 */

namespace Drupal\identity_contact\AccessControl;

use Drupal\Core\Entity\EntityAccessControlHandler;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Access\AccessResult;

/**
 * Access controller for identity_contact entity.
 */
class ContactAccessControl extends EntityAccessControlHandler {

  /**
   * {@inheritdoc}
   */
  protected function checkAccess(EntityInterface $entity, $operation, AccountInterface $account) {
    /** @var \Drupal\identity_contact\ContactInterface $entity */
    $account = $this->prepareUser($account);

    if ($account->isAnonymous()) {
      return AccessResult::neutral();
    }

    if (in_array($operation, ['view', 'update', 'delete'])) {
      if ($account->hasPermission("$operation any identity_contact")) {
        return AccessResult::allowed();
      }

      if ($owner = $entity->getOwner()) {
        if (($account->id() == $owner->id()) && $account->hasPermission("$operation own identity_contact")) {
          return AccessResult::allowed();
        }
      }
    }

    return AccessResult::neutral();
  }

}
