<?php

/**
 * @file
 * Contains \Drupal\content_entity_example\Entity\Controller\ContentEntityExampleController.
 */

namespace Drupal\content_entity_example\Entity\Controller;

use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityListBuilder;

/**
 * Provides a list controller for content_entity_example entity.
 *
 * @ingroup content_entity_example
 */
class ContactListController extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = t('ContactID');
    $header['name'] = t('Name');
    $header['first_name'] = t('First Name');
    $header['gender'] = t('Gender');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\content_entity_example\Entity\Contact */
    $row['id'] = $entity->id();
    $row['name'] = \Drupal::l($this->getLabel($entity),
      'contact.view', array(
        'content_entity_example_contact' => $entity->id(),
      ));
    $row['first_name'] = $entity->first_name->value;
    $row['gender'] = $entity->gender->value;
    return $row + parent::buildRow($entity);
  }
}
