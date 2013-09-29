<?php

/**
 * @file
 * Definition of Drupal\animal\Entity\Animal.
 */

namespace Drupal\animal\Entity;

use Drupal\Core\Entity\EntityStorageControllerInterface;
use Drupal\Core\Entity\EntityMalformedException;
use Drupal\Core\Entity\EntityNG;
use Drupal\animal\AnimalInterface;



/**
 * Defines the animal entity class.
 *
 * @EntityType(
 *   id = "animal",
 *   label = @Translation("Animal"),
 *   module = "animal",
 *   controllers = {
 *     "storage" = "Drupal\animal\AnimalStorageController",
 *     "access" = "Drupal\animal\AnimalAccessController",
 *     "render" = "Drupal\Core\Entity\EntityRenderController",
 *     "form" = {
 *       "default" = "Drupal\animal\AnimalFormController"
 *     },
 *     "translation" = "Drupal\animal\ProfileTranslationController"
 *   },
 *   base_table = "animal",
 *   uri_callback = "animal_uri",
 *   route_base_path = "admin/config/animal/",
 *   label_callback = "animal_label",
 *   fieldable = TRUE,
 *   translatable = TRUE,
 *   entity_keys = {
 *     "id" = "aid",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "canonical" = "/zoo/animal/{animal}",
 *     "edit-form" = "/zoo/animal/{animal}/edit"
 *   }
 * )
 */
class Animal extends EntityNG implements AnimalInterface {
  /**
   * {@inheritdoc}
   */
  public function id() {
    return $this->get('aid')->value;
  }

  /**
   * {@inheritdoc}
   */
  static function preCreate(EntityStorageControllerInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);

    if (!isset($values['created'])) {
      $values['created'] = REQUEST_TIME;
    }
    $values['type'] ='test';
  }

  /**
   * {@inheritdoc}
   */
  public function preSave(EntityStorageControllerInterface $storage_controller) {
    parent::preSave($storage_controller);


  }

  /**
   * {@inheritdoc}
   */
  public function postSave(EntityStorageControllerInterface $storage_controller, $update = TRUE) {
    parent::postSave($storage_controller, $update);
  }

  /**
   * {@inheritdoc}
   */
  public static function postDelete(EntityStorageControllerInterface $storage_controller, array $entities) {
    parent::postDelete($storage_controller, $entities);
  }

  /**
   * {@inheritdoc}
   */
  public function activate() {
    $this->get('status')->value = 1;
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getAnimalName() {
    $name = $this->get('name')->value ?: \Drupal::config('user.settings')->get('anonymous');
    \Drupal::moduleHandler()->alter('user_format_name', $name, $this);
    return $name;
  }

  /**
   * {@inheritdoc}
   */
  public function setAnimalName($animal_name) {
    $this->set('name', $animal_name);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getChangedTime() {
    return $this->get('changed')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions($entity_type) {
    $properties['aid'] = array(
      'label' => t('Animal ID'),
      'description' => t('The animal ID.'),
      'type' => 'integer_field',
      'read-only' => TRUE,
    );
    $properties['uid'] = array(
      'label' => t('User ID'),
      'description' => t('The user ID.'),
      'type' => 'integer_field',
      'read-only' => TRUE,
    );
    $properties['uuid'] = array(
      'label' => t('UUID'),
      'description' => t('The user UUID.'),
      'type' => 'uuid_field',
      'read-only' => TRUE,
    );
    $properties['name'] = array(
      'label' => t('Name'),
      'description' => t('The name of this user'),
      'type' => 'string_field',
      'settings' => array('default_value' => ''),
      'property_constraints' => array(
        // No Length contraint here because the UserName constraint also covers
        // that.
        'value' => array(
          'UserName' => array(),
          'UserNameUnique' => array(),
        ),
      ),
    );
    $properties['created'] = array(
      'label' => t('Created'),
      'description' => t('The time that the node was created.'),
      'type' => 'integer_field',
    );
    $properties['status'] = array(
      'label' => t('User status'),
      'description' => t('Whether the user is active (1) or blocked (0).'),
      'type' => 'boolean_field',
      'settings' => array('default_value' => 1),
    );
    return $properties;
  }

}