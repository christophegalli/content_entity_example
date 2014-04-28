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
class ContentEntityExampleListController extends EntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['id'] = t('ContentEntityExampleID');
    $header['label'] = t('Label');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /* @var $entity \Drupal\content_entity_example\Entity\ContentEntityExample */
    $row['id'] = $entity->id();
    $row['label'] = \Drupal::l($this->getLabel($entity),
      'content_entity_example.view', array(
        'content_entity_example' => $entity->id(),
      ));
    return $row + parent::buildRow($entity);
  }
}
