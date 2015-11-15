<?php

/**
 * @file
 * Contains \Drupal\profile\Plugin\Derivative\ProfileLocalTask.
 */

namespace Drupal\profile\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverBase;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Plugin\Discovery\ContainerDeriverInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Provides dynamic routes to add/edit/list profiles.
 */
class ProfileLocalTask extends DeriverBase implements ContainerDeriverInterface {

  /**
   * Stores the profile type config objects.
   *
   * @var \Drupal\Core\Config\Config
   */
  protected $config;

  /**
   * Constructs a new ProfileAddLocalTask.
   *
   * @param \Drupal\Core\Config\ConfigFactoryInterface $config_factory
   *   The config factory.
   */
  public function __construct($base_plugin_definition, ConfigFactoryInterface $config_factory) {
    $this->config = $config_factory->loadMultiple($config_factory->listAll('profile.type'));
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container, $base_plugin_definition) {
    return new static(
      $base_plugin_definition,
      $container->get('config.factory')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $this->derivatives = [];

    foreach ($this->config as $profile_type_id => $profile_type) {
      $this->derivatives[$profile_type_id] = array(
          'title' => $profile_type->get('label'),
          'route_name' => "entity.$profile_type_id.user_profile_form",
          'parent_id' => 'entity.user.edit_form',
          'route_parameters' => array('profile_type' => $profile_type->get('id')),
        ) + $base_plugin_definition;
    }

    return $this->derivatives;
  }

}
