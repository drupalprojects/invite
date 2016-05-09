<?php

namespace Drupal\invite\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\invite\Entity\Invite;

/**
 * Class InviteBlockForm.
 *
 * @package Drupal\invite\Form
 */
class InviteBlockForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'invite_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['email'] = array(
      '#type' => 'email',
      '#title' => $this->t('Email'),
    );

    $form['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Send'),
      '#name' => 'send',
      '#limit_validation_errors' => array(),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $invite_type = $form_state->getBuildInfo()['args'][0];
    $invite = Invite::create(array('type' => $invite_type));
    $invite->save();
  }

}
