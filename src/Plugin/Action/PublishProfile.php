<?php

/**
 * @file
 * Contains \Drupal\profile\Plugin\Action\PublishProfile.
 */

namespace Drupal\profile\Plugin\Action;

use Drupal\Core\Action\ActionBase;
use Drupal\Core\Session\AccountInterface;

/**
 * Publishes a profile.
 *
 * @Action(
 *   id = "profile_publish_action",
 *   label = @Translation("Publish selected profile"),
 *   type = "profile"
 * )
 */
class PublishProfile extends ActionBase {

  /**
   * {@inheritdoc}
   */
  public function execute($entity = NULL) {
    $entity->status = PROFILE_ACTIVE;
    $entity->save();
  }

  /**
   * {@inheritdoc}
   */
  public function access($object, AccountInterface $account = NULL, $return_as_object = FALSE) {
    /** @var \Drupal\node\NodeInterface $object */
    $result = $object->access('update', $account, TRUE)
      ->andIf($object->status->access('edit', $account, TRUE));

    return $return_as_object ? $result : $result->isAllowed();
  }

}
