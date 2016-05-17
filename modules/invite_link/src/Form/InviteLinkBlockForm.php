<?php

namespace Drupal\invite_link\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\invite\Entity\Invite;

/**
 * Class InviteLinkBlockForm.
 *
 * @package Drupal\invite\Form
 */
class InviteLinkBlockForm extends FormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'invite_link_block_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['create_invite'] = array(
      '#type' => 'button',
      '#value' => $this->t('Create invite'),
      '#limit_validation_errors' => array(array('create_invite')),
      '#executes_submit_callback' => TRUE,
      '#ajax' => array(
        'callback' => '::ajaxReplaceInviteContainer',
        'wrapper' => 'invite',
        'method' => 'replace',
      ),
    );

    $form['invite_containter'] = array(
      '#type' => 'container',
      '#prefix' => '<div id="invite">',
      '#suffix' => '</div>',
    );

    return $form;
  }

  public function ajaxReplaceInviteContainer($form, FormStateInterface $form_state) {
    $invite_type = $form_state->getBuildInfo()['args'][0];
    $invite = Invite::create(array('type' => $invite_type));
    $invite->save();
    $url = Url::fromRoute('invite.invite_accept_accept');
    $form['invite_container']['#markup'] = '<a href="/invite/accept/' . $invite->getRegCode() . '">Invite Link</a>';
    return $form['invite_container'];
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
