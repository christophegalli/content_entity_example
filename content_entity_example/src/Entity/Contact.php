<?php
/**
 * @file
 * Contains \Drupal\content_entity_example\Entity\ContentEntityExample.
 */

namespace Drupal\content_entity_example\Entity;

use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\FieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\content_entity_example\ContactInterface;
use Drupal\user\UserInterface;
/**
 * Defines the ContentEntityExample entity.
 *
 * @ingroup content_entity_example
 *
 * This is the main definition of the entity type. From it, an entityType is derived. The most important
 * properties in this example are:
 *
 * - id:          The unique identifier of this entityType. It follows the pattern 'moduleName_xyz'
 *                to avoid naming conflicts.
 *
 * - label:       Human readable name of the entity type.
 *
 * - controllers: Controller classes are used for different tasks. You can use standard controllers
 *                provided by D8 or build your own controller, most probably derived from the standard class.
 *
 *                view_builder: we use the standard controller to view an instance. It is called when
 *                              a route lists an '_entity_view' default for the entityType (see routing.yml for details.
 *                              The view can be manipulated by using the standard drupal tools in the settings.
 *                list builder: We derive out own list builder class from the entityListBuilder to control the
 *                              presentation.
 *                              If there is a view available for this entity from the views module, it
 *                              overrides the list builder. @todo: any view? naming convention?
 *                form:         We derive our own forms to add functionality like additional fields, redirects etc.
 *                              They are called when the routing list an '_entity_form' default for the entityType.
 *                              Depending on the suffix (.add/.edit/.delete)  in the route, the correct from is called.
 *                access:       Our won accessController where we determine access rights based on permissions.
 *
 *  - base_table: Define the name of the table used to store the data. Make sure it is unique. The schema is
 *                automatically determined from the BaseFieldDefinitions below. The table is automatically created
 *                during installation.
 *
 *  - fieldable:  Can additional fields be added to the entity via the GUI. Analog to content types.
 *
 *  - entity_keys:How to access the fields. Analog to 'nid' or 'uid'.
 *
 *  - links:      Provide links to do standard tasks. The 'edit-form' and 'delete-form' links are added to the
 *                list built by the entityListController. They will show up as action buttons in an additional column.
 *
 *  There are many more properties to be used in an entity type definition. For a complete overview, please refer
 *  to the '\Drupal\Core\Entity\EntityType' class definition.
 *
 *
 * @ContentEntityType(
 *   id = "content_entity_example_contact",
 *   label = @Translation("Contact entity"),
 *   controllers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\content_entity_example\Entity\Controller\ContactListController",
 *
 *     "form" = {
 *       "add" = "Drupal\content_entity_example\Form\ContactForm",
 *       "edit" = "Drupal\content_entity_example\Form\ContactForm",
 *       "delete" = "Drupal\content_entity_example\Form\ContactDeleteForm",
 *     },
 *     "access" = "Drupal\content_entity_example\ContactAccessController",
 *   },
 *   base_table = "contact",
 *   admin_permission = "administer content_entity_example entity",
 *   fieldable = TRUE,
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "uuid" = "uuid"
 *   },
 *   links = {
 *     "edit-form" = "content_entity_example.contact_edit",
 *     "admin-form" = "content_entity_example.contact_settings",
 *     "delete-form" = "content_entity_example.contact_delete"
 *   }
 * )
 *
 * The 'Contact' class defines methods and fields for the contact entity.
 *
 * Being derived from the ContentEntityBase class, we can override the methods we want. In our case we want to
 * provide access to the standard fields about creation and changed time stamps.
 *
 * Our interface (see ContactInterface) also exposes the EntityOwnerInterface. This allows us to provide methods
 * for setting and providing ownership information.
 *
 * The most important part is the definitions of the field properties for this entity type. These are of the
 * same type as fields added through the GUI, but they can by changed in code. In the definition we can define
 * if the user with the rights privileges can influence the presentation (view, edit) of each field.
 *
 */
class Contact extends ContentEntityBase implements ContactInterface {

  /**
   * {@inheritdoc}
   *
   * When a new entity instance is added, make sure that the user_id entity reference points to the current
   * user as the creator of the instance.
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
   *
   * Define the field properties here.
   *
   * Field name, type and size determine the table structure.
   *
   * In addition, we can define how the field and its content can be manipulated in the GUI. The behaviour
   * of the widgets used can be determined here.
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    // Standard field, used as unique if primary index.
    $fields['id'] = FieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Contact entity.'))
      ->setReadOnly(TRUE);

    // Standard field, unique outside of the scope of the current project
    $fields['uuid'] = FieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Contact entity.'))
      ->setReadOnly(TRUE);

    /**
     * Name field for the contact.
     *
     * We  set display options for the vew as well as the form.
     * We determine that the user with the correct privileges can change the view and
     * edit (form) configuration.
     */
    $fields['name'] = FieldDefinition::create('string')
      ->setLabel(t('Name'))
      ->setDescription(t('The name of the Contact entity.'))
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

    $fields['first_name'] = FieldDefinition::create('string')
      ->setLabel(t('First Name'))
      ->setDescription(t('The first name of the Contact entity.'))
      ->setSettings(array(
        'default_value' => '',
        'max_length' => 255,
        'text_processing' => 0,
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'string',
        'weight' => -5,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    /**
     * Gender field for the contact.
     *
     * Instead of a standard string type we chose a listTextType with a drop down menu widget. The values shown
     * in the menu are 'male' and 'female'.
     *
     * In the view the field content is shown as string.
     * In the form the choices are presented as options list.
     */
    $fields['gender'] = FieldDefinition::create('list_text')
      ->setLabel(t('Gender'))
      ->setDescription(t('The gender of the Contact entity.'))
      ->setSettings(array(
        'allowed_values' => array(
          'female' => 'female',
          'male' => 'male',
        ),
      ))
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'string',
        'weight' => -4,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'options_select',
        'weight' => -4,
      ))
      ->setDisplayConfigurable('form', TRUE)
      ->setDisplayConfigurable('view', TRUE);

    /**
     * Owner field of the contact.
     *
     * Set up as an entity reference field which holds the reference to the user object of the owner.
     * The view shows the user name field of the user.
     * The form presents a autocomplete field for the user name
     */
    $fields['user_id'] = FieldDefinition::create('entity_reference')
      ->setLabel(t('User Name'))
      ->setDescription(t('The Name of the associated user.'))
      ->setSetting('target_type', 'user')
      ->setSetting('handler', 'default')
      ->setDisplayOptions('view', array(
        'label' => 'above',
        'type' => 'entity_reference',
        'weight' => -3,
      ))
      ->setDisplayOptions('form', array(
        'type' => 'entity_reference_autocomplete',
        'settings' => array(
          'match_operator' => 'CONTAINS',
          'size' => 60,
          'autocomplete_type' => 'tags',
          'placeholder' => '',
        ),
        'weight' => -3,
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
