<?php

/**
 * @file
 * Contains \Drupal\comment\Entity\CommentInterface.
 */

namespace Drupal\animal;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;

/**
 * Provides an interface defining a comment entity.
 */
interface AnimalInterface extends ContentEntityInterface, EntityChangedInterface {}
