<?php

/**
 * @file
 * Definition of Drupal\user\UserStorageController.
 */

namespace Drupal\animal;

use Drupal\Component\Uuid\UuidInterface;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Database\Connection;
use Drupal\field\FieldInfo;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\DatabaseStorageControllerNG;
use Drupal\Core\Entity\EntityStorageControllerInterface;


/**
 * Controller class for users.
 *
 * This extends the Drupal\Core\Entity\DatabaseStorageController class, adding
 * required special handling for user objects.
 */
class AnimalStorageController extends DatabaseStorageControllerNG implements EntityStorageControllerInterface {

  /**
   * Provides the password hashing service object.
   *
   * @var \Drupal\Core\Password\PasswordInterface
   */
  protected $password;

  /**
   * Provides the user data service object.
   *
   * @var \Drupal\user\UserDataInterface
   */
  protected $userData;

  /**
   * Constructs a new UserStorageController object.
   *
   * @param string $entity_type
   *  The entity type for which the instance is created.
   * @param array $entity_info
   *   An array of entity info for the entity type.
   * @param \Drupal\Core\Database\Connection $database
   *   The database connection to be used.
   * @param \Drupal\field\FieldInfo $field_info
   *   The field info service.
   * @param \Drupal\Component\Uuid\UuidInterface $uuid_service
   *   The UUID Service.
   */
  public function __construct($entity_type, $entity_info, Connection $database, FieldInfo $field_info, UuidInterface $uuid_service) {
    parent::__construct($entity_type, $entity_info, $database, $field_info, $uuid_service);
  }

  /**
   * {@inheritdoc}
   */
  public static function createInstance(ContainerInterface $container, $entity_type, array $entity_info) {
    return new static(
      $entity_type,
      $entity_info,
      $container->get('database'),
      $container->get('field.info'),
      $container->get('uuid')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function save(EntityInterface $entity) {
    if (!$entity->id()) {
      $entity->uid->value = $this->database->nextId($this->database->query('SELECT MAX(uid) FROM {users}')->fetchField());
      $entity->enforceIsNew();
    }
    parent::save($entity);
  }

}
