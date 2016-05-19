<?php

namespace Drupal\invite\Entity;

use Drupal\Core\Database\Database;
use Drupal\Core\Entity\EntityStorageInterface;
use Drupal\Core\Field\BaseFieldDefinition;
use Drupal\Core\Entity\ContentEntityBase;
use Drupal\Core\Entity\EntityChangedTrait;
use Drupal\Core\Entity\EntityTypeInterface;
use Drupal\invite\InviteTypeInterface;
use Drupal\user\UserInterface;

/**
 * Defines the Invite type entity.
 *
 * @ingroup invite
 *
 * @ContentEntityType(
 *   id = "invite_type",
 *   label = @Translation("Invite type"),
 *   handlers = {
 *     "view_builder" = "Drupal\Core\Entity\EntityViewBuilder",
 *     "list_builder" = "Drupal\invite\InviteTypeListBuilder",
 *     "views_data" = "Drupal\invite\Entity\InviteTypeViewsData",
 *
 *     "form" = {
 *       "default" = "Drupal\invite\Form\InviteTypeForm",
 *       "add" = "Drupal\invite\Form\InviteTypeForm",
 *       "edit" = "Drupal\invite\Form\InviteTypeForm",
 *       "delete" = "Drupal\invite\Form\InviteTypeDeleteForm",
 *     },
 *     "access" = "Drupal\invite\InviteTypeAccessControlHandler",
 *     "route_provider" = {
 *       "html" = "Drupal\invite\InviteTypeHtmlRouteProvider",
 *     },
 *   },
 *   base_table = "invite_type",
 *   admin_permission = "administer invite type entities",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "uuid" = "uuid",
 *     "uid" = "user_id",
 *   },
 *   links = {
 *     "canonical" = "/admin/structure/invite_type/{invite_type}",
 *     "add-form" = "/admin/structure/invite_type/add",
 *     "edit-form" = "/admin/structure/invite_type/{invite_type}/edit",
 *     "delete-form" = "/admin/structure/invite_type/{invite_type}/delete",
 *     "collection" = "/admin/structure/invite_type",
 *   },
 *   field_ui_base_route = "invite_type.settings"
 * )
 */
class InviteType extends ContentEntityBase implements InviteTypeInterface {
  use EntityChangedTrait;

  /**
   * {@inheritdoc}
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
  public static function preDelete(EntityStorageInterface $storage, array $entities) {
    parent::postDelete($storage, $entities);
    $entity = reset($entities);

    // Remove invite_sender records.
    $invite_senders = Database::getConnection()->query('SELECT id FROM invite_sender WHERE type=:type', array(':type' => $entity->getType()))->fetchAll(\PDO::FETCH_COLUMN);
    $invite_senders = InviteSender::loadMultiple($invite_senders);
    foreach ($invite_senders as $invite_sender) {
      $invite_sender->delete();
    }
    // Reload blocks.
    drupal_flush_all_caches(); // todo flush block caches specifically.
  }


  /**
   * {@inheritdoc}
   */
  public function label() {
    return $this->get('label')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setLabel($label) {
    $this->set('label', $label);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getType() {
    return $this->get('type')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setType($type) {
    $this->set('type', $type);
    return $this;
  }

  /**
   * {@inheritdoc}
   */
  public function getData() {
    return $this->get('data')->value;
  }

  /**
   * {@inheritdoc}
   */
  public function setData($data) {
    $this->set('data', $data);
    return $this;
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
  public function setCreatedTime($timestamp) {
    $this->set('created', $timestamp);
    return $this;
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
   */
  public function isPublished() {
    return (bool) $this->getEntityKey('status');
  }

  /**
   * {@inheritdoc}
   */
  public function setPublished($published) {
    $this->set('status', $published ? NODE_PUBLISHED : NODE_NOT_PUBLISHED);
    return $this;
  }

  public function getDescription() {
    return $this->get('description')->value;
  }

  /**
   * {@inheritdoc}
   */
  public static function baseFieldDefinitions(EntityTypeInterface $entity_type) {

    $fields['id'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('ID'))
      ->setDescription(t('The ID of the Invite type entity.'))
      ->setReadOnly(TRUE);

    $fields['uuid'] = BaseFieldDefinition::create('uuid')
      ->setLabel(t('UUID'))
      ->setDescription(t('The UUID of the Invite type entity.'))
      ->setReadOnly(TRUE);

    $fields['label'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Invite type name'))
      ->setDescription(t('The human-readable name of this type.'))
      ->setSettings(array(
        'max_length' => 80,
        'text_processing' => 0,
      ));

    $fields['type'] = BaseFieldDefinition::create('string')
      ->setLabel(t('Type'))
      ->setDescription(t('The invite type.'))
      ->setReadOnly(TRUE)
      ->setSettings(array(
        'max_length' => 80,
        'text_processing' => 0,
      ));

    $fields['description'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Description'))
      ->setDescription(t('A brief description of htis type.'));

    $fields['data'] = BaseFieldDefinition::create('string_long')
      ->setLabel(t('Data'))
      ->setDescription(t('Stores axiliary data.'));

    $fields['status'] = BaseFieldDefinition::create('integer')
      ->setLabel(t('Expiration'))
      ->setDescription(t('Invitation status'))
      ->setSettings(array(
        'max_length' => 11,
        'text_processing' => 0,
      ));

    $fields['langcode'] = BaseFieldDefinition::create('language')
      ->setLabel(t('Language code'))
      ->setDescription(t('The paragraphs entity language code.'))
      ->setRevisionable(TRUE);

    $fields['changed'] = BaseFieldDefinition::create('changed')
      ->setLabel(t('Changed'))
      ->setDescription(t('The time that the Paragraph was last edited.'))
      ->setRevisionable(TRUE)
      ->setTranslatable(TRUE);

    return $fields;
  }

}
