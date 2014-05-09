<?php
/**
 * @file
 * Contains \Drupal\content_entity_example\Entity\ContentEntityExample.
 */

namespace Drupal\content_entity_example_contact\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\content_entity_example_contact\ContactInterface;
use Drupal\user\UserInterface;
/**
 * Defines the ContentEntityExample entity.
 *
 * @ingroup content_entity_example
 *
 * @ContentEntityType(
 *   id = "content_entity_example_contact",
 *   label = @Translation("ContentEntityExample entity"),
 *   controllers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\content_entity_example\Entity\Controller\ContentEntityExampleListController",
 *
 *     "form" = {
 *       "add" = "Drupal\content_entity_example\Entity\Form\ContentEntityExampleForm",
 *       "edit" = "Drupal\content_entity_example\Entity\Form\ContentEntityExampleForm",
 *       "delete" = "Drupal\content_entity_example\Entity\Form\ContentEntityExampleDeleteForm",
 *     },
 *     "access" = "Drupal\content_entity_example\ContentEntityExampleAccessController",
 *   },
 *   base_table = "content_entity_example",
 *   admin_permission = "administer content_entity_example entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "edit-form" = "content_entity_example.edit",
 *     "admin-form" = "content_entity_example.settings",
 *     "delete-form" = "content_entity_example.delete"
 *   }
 * )
 */
class Contact extends ContentEntityBase implements ContentEntityExampleInterface {

  /**
   * {@inheritdoc}
   */
  public static function preCreate(EntityStorageInterface $storage_controller, array &$values) {
    parent::preCreate($storage_controller, $values);
    $values += array(
      'user_id' => \Drupal::currentUser()->id(),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getCreatedTime() {
    return $this->get('created')->value;
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
  public function getOwner() {
    return $this->get('user_id')->entity;
  }

  /**
   * {@inheritdoc}
   */
  public function getOwnerId() {
    return $this->get('user_id')->target_id;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwnerId($uid) {
    $this->set('user_id', $uid);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function setOwner(UserInterface $account) {
    $this->set('user_id', $account->id());
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {
    $fields['id'] = FieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the ContentEntityExample entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = FieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the ContentEntityExample entity.'))
      ->setReadOnly(TRUE);

    $fields['name'] = FieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the ContentEntityExample entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['user_id'] = FieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => -6,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
        'weight' => -6,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    $fields['langcode'] = FieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The language code of ContentEntityExample entity.'));
    $fields['created'] = FieldDefinition::create('created')
      ->setLabel(t('Created'))
      ->setDescription(t('The time that the entity was created.'));

    $fields['changed'] = FieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the entity was last edited.'));

    return $fields;
  }
}
