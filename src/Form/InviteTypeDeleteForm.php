<?php

namespace Drupal\invite\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\ContentEntityDeleteForm;
use Drupal\Core\Entity\Entity;
use Drupal\Core\Form\FormStateInterface;
use Drupal\invite\Entity\InviteSender;

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
    $invite_senders = Database::getConnection()->query('SELECT id FROM invite_sender WHERE type=:type', array(':type' => $this->entity->getType()))->fetchAll(\PDO::FETCH_COLUMN);
    $invite_senders = InviteSender::loadMultiple($invite_senders);
    foreach ($invite_senders as $invite_sender) {
      $invite_sender->delete();
    }
    parent::submitForm($form, $form_state);
  }

}
