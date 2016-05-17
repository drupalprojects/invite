<?php

namespace Drupal\invite_by_email\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\invite\Entity\Invite;
use Drupal\invite\Entity\InviteType;

/**
 * Class InviteByEmailBlockForm.
 *
 * @package Drupal\invite\Form
 */
class InviteByEmailBlockForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'invite_by_email_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $invite_type = InviteType::load(Database::getConnection()->query('SELECT id FROM {invite_type} WHERE type = :type', array(':type' => $form_state->getBuildInfo()['args'][0]))->fetchField());
    $data = unserialize($invite_type->getData());

    $form['email'] = array(
      '#type' => 'email',
      '#required' => TRUE,
      '#title' => t('Email'),
    );

    if (!$data['use_default'] && $data['subject_editable']) {
      $invite_email_subject_default = \Drupal::service('entity.manager')->getFieldDefinitions('invite', 'invite')['field_invite_email_subject']->getDefaultValueLiteral()[0]['value'];

      $form['email_subject'] = array(
        '#type' => 'textfield',
        '#required' => TRUE,
        '#title' => t('Email subject'),
        '#default_value' => $invite_email_subject_default,
      );
    }

    $form['send_invitation'] = array(
      '#type' => 'submit',
      '#value' => t('Send Invitation'),
    );

    return $form;
  }


  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $invite_type = $form_state->getBuildInfo()['args'][0];
    $invite = Invite::create(array('type' => $invite_type));
    $invite->field_invite_email_address->value = $form_state->getValue('email');
    $subject = $form_state->getValue('email_subject');
    if (!empty($subject)) {
      $invite->field_invite_email_subject->value = $subject;
    }
    $invite->setPlugin('invite_by_email');
    $invite->save();
  }
}
