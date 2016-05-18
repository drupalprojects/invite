<?php

namespace Drupal\invite\Form;

use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a form for deleting Invite type entities.
 *
 * @ingroup invite
 */
class InviteTypeDeleteForm extends ContentEntityDeleteForm {

  /**
   * When an InviteType is deleted, also remove the corresponding InviteSender.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    parent::submitForm($form, $form_state);
  }

}
