<?php

namespace Drupal\invite\Form;

use Drupal\Core\Database\Connection;
use Drupal\Core\Entity\ContentEntityForm;
use Drupal\Core\Entity\EntityManagerInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\invite\Entity\InviteSender;
use Drupal\invite\InvitePluginManager;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Form controller for Invite type edit forms.
 *
 * @ingroup invite
 */
class InviteTypeForm extends ContentEntityForm {

  /**
   * @var \Drupal\invite\InvitePluginManager
   */
  public $pluginManager;

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity.manager'),
      $container->get('plugin.manager.invite'),
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function __construct(EntityManagerInterface $entity_manager, InvitePluginManager $plugin_manager, Connection $database) {
    parent::__construct($entity_manager);
    $this->pluginManager = $plugin_manager;
    $this->database = $database;
  }

  /**
   * Helper function to load the default send method for the invite type.
   */
  public function getDefaultSendMethods($invite_type) {
    $defaults = array();
    $invite_senders = $this->database->query('SELECT name FROM invite_sender WHERE type=:type', array(':type' => $invite_type->getType()))
      ->fetchAllAssoc('name');
    foreach ($invite_senders as $invite_sender) {
      $defaults[$invite_sender->name] = $invite_sender->name;
    }

    return $defaults;
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    /* @var $entity \Drupal\invite\Entity\InviteType */
    $form = parent::buildForm($form, $form_state);
    $entity = $this->entity;
    $is_new = $entity->isNew();

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
      '#default_value' => $entity->getType(),
      '#maxlength' => 255,
      '#disabled' => !$is_new,
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

    // List the available sending methods.
    $plugin_definitions = $this->pluginManager->getDefinitions();
    if (!empty($plugin_definitions)) {
      $options = array();
      foreach ($plugin_definitions as $plugin_definition) {
        $options[$plugin_definition['provider']] = $plugin_definition['id'];
      }
      $default_send_method = array();
      if (!$is_new) {
        $default_send_method = $this->getDefaultSendMethods($entity);
      }
      $form['send_method'] = array(
        '#type' => 'checkboxes',
        '#required' => TRUE,
        '#title' => t('Sending Method'),
        '#default_value' => $default_send_method,
        '#options' => $options,
      );
    }
    else {
      $form['send_method'] = array(
        '#type' => 'item',
        '#markup' => $this->t('Please enable a sending method module such as Invite by email.'),
      );
      $form['actions']['submit']['#disabled'] = TRUE;

    }

    return $form;
  }

  /**
   * Helper method to add an invite_sender record.
   */
  public function updateInviteSender($send_methods, $invite_type) {
    $type = $invite_type->getType();
    $this->database->delete('invite_sender')->condition('type', $type)->execute();
    foreach ($send_methods as $send_method) {
      if (!empty($send_method)) {
        InviteSender::create(array(
            'type' => $type,
            'module' => '', // <-- todo
            'name' => $send_method,
          )
        )->save();
      }
    }
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $entity = $this->entity;
    // Add/update sending invite_sender.
    try {
      $this->updateInviteSender($form_state->getValue('send_method'), $entity);
    }
    catch (\Exception $e) {
      throw $e;
    }

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
    drupal_flush_all_caches(); //@todo flush block cache only.
    $form_state->setRedirect('entity.invite_type.collection', ['invite_type' => $entity->id()]);
  }


}
