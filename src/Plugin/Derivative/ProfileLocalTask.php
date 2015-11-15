<?php

/**
 * @file
 * Contains \Drupal\profile\Plugin\Derivative\ProfileLocalTask.
 */

namespace Drupal\profile\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides dynamic routes to add/edit/list profiles.
 */
class ProfileLocalTask extends DeriverBase implements ContainerDeriverInterface {

  /**
   * The entity manager service.
   *
   * @var \Drupal\Core\Entity\EntityManagerInterface
   */
  protected $entityManager;

  /**
   * Constructs a new ProfileAddLocalTask.
   *
   * @param \Drupal\Core\Entity\EntityManagerInterface $entity_manager
   *   The entity manager.
   */
  public function __construct($base_plugin_definition, EntityManagerInterface $entity_manager) {
    $this->entityManager = $entity_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_definition) {
    return new static(
      $base_plugin_definition,
      $container->get('entity.manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $this->derivatives = [];

    foreach ($this->entityManager->getStorage('profile_type')->loadMultiple() as $profile_type_id => $profile_type) {
      $this->derivatives["profile.type.$profile_type_id"] = [
          'title' => $profile_type->label(),
          'route_name' => "entity.profile.type.$profile_type_id.user_profile_form",
          'parent_id' => 'entity.user.edit_form',
          'route_parameters' => array('profile_type' => $profile_type_id),
        ] + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
