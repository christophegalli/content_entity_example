<?php
/**
 * @file
 * Definition of Drupal\content_entity_example\Entity\Form\ContentEntityExampleFormController.
 */

namespace Drupal\content_entity_example\Entity\Form;

use Drupal\Core\Entity\ContentEntityFormController;
use Drupal\Core\Language\Language;

/**
 * Form controller for the content_entity_example entity edit forms.
 *
 * @ingroup content_entity_example
 */
class ContentEntityExampleFormController extends ContentEntityFormController {

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::buildForm().
   */
  public function buildForm(array $form, array &$form_state) {
    /* @var $entity \Drupal\content_entity_example\Entity\ContentEntityExample */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['user_id'] = array(
      '#type' => 'textfield',
      '#title' => 'UID',
      '#default_value' => $entity->user_id->target_id,
      '#size' => 60,
      '#maxlength' => 128,
      '#required' => TRUE,
      '#weight' => -10,
    );

    $form['langcode'] = array(
      '#title' => t('Language'),
      '#type' => 'language_select',
      '#default_value' => $entity->getUntranslated()->language()->id,
      '#languages' => Language::STATE_ALL,
    );

    return $form;
  }

  /**
   * Overrides \Drupal\Core\Entity\EntityFormController::submit().
   */
  public function submit(array $form, array &$form_state) {
    // Build the entity object from the submitted values.
    $entity = parent::submit($form, $form_state);
    $form_state['redirect_route']['route_name'] = 'content_entity_example.list';

    return $entity;
  }

  /**
   * Overrides Drupal\Core\Entity\EntityFormController::save().
   */
  public function save(array $form, array &$form_state) {
    $entity = $this->entity;
    $entity->save();
  }
}
