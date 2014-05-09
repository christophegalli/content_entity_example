<?php
/**
 * @file
 * Contains \Drupal\content_entity_example\ContentEntityExampleInterface.
 */

namespace Drupal\content_entity_example_contact;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\user\EntityOwnerInterface;
/**
 * Provides an interface defining a ContentEntityExample entity.
 * @ingroup content_entity_example
 */
interface ContactInterface extends ContentEntityInterface, EntityOwnerInterface {

}
