<?php

/**
 * @file
 * Contains \Drupal\content_entity_example\Entity\Form\ContactDeleteForm
 */

namespace Drupal\content_entity_example\Form;

use Drupal\Core\Entity\ContentEntityConfirmFormBase;

/**
 * Provides a form for deleting a content_entity_example entity.
 *
 * @ingroup content_entity_example
 */
class ContactDeleteForm extends ContentEntityConfirmFormBase {


  /**
   * {@inheritdoc}
   */
  public function getQuestion() {
    return t('Are you sure you want to delete entity %name?', array('%name' => $this->entity->label()));
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelRoute() {
    return array(
      'route_name' => 'content_entity_example.contact_list',
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function submit(array $form, array &$form_state) {
    $this->entity->delete();

    watchdog('content', '@type: deleted %title.', array('@type' => $this->entity->bundle(), '%title' => $this->entity->label()));
    $form_state['redirect_route']['route_name'] = 'content_entity_example.contact_list';
  }

}
