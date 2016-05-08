<?php

namespace Drupal\invite\Form;

use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Form controller for Invite type edit forms.
 *
 * @ingroup invite
 */
class InviteTypeForm extends ContentEntityForm {
  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\invite\Entity\InviteType */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;

    $form['label'] = array(
      '#title' => t('Invite Type Label'),
      '#type' => 'textfield',
      '#default_value' => $entity->label(),
      '#description' => t('The human-readable name of this invite type. This name must be unique.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    $form['type'] = array(
      '#type' => 'machine_name',
      '#default_value' => $entity->id(),
      '#maxlength' => 255,
      '#disabled' => !$entity->isNew(),
      '#machine_name' => array(
        'exists' => ['Drupal\invite\Entity\InviteType', 'load'],
        'source' => array('label'),
      ),
      '#description' => t('A unique machine-readable name for this invite type. It must only contain lowercase letters, numbers, and underscores.'),
    );

    $form['description'] = array(
      '#type' => 'textarea',
      '#title' => t('Description'),
      '#description' => t('Description about the invite type.'),
      '#rows' => 5,
      '#default_value' => $entity->getDescription(),
    );

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    $status = parent::save($form, $form_state);

    switch ($status) {
      case SAVED_NEW:
        drupal_set_message($this->t('Created the %label Invite type.', [
          '%label' => $entity->label(),
        ]));
        break;

      default:
        drupal_set_message($this->t('Saved the %label Invite type.', [
          '%label' => $entity->label(),
        ]));
    }
    $form_state->setRedirect('entity.invite_type.collection', ['invite_type' => $entity->id()]);
  }

}
