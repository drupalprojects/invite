<?php

namespace Drupal\invite;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Invite type entities.
 *
 * @ingroup invite
 */
interface InviteTypeInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  /**
   * Gets the Invite type label.
   *
   * @return string
   *   Label of the Invite type.
   */
  public function label();

  /**
   * Sets the Invite type label.
   *
   * @param string $label
   *   The Invite type label.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite type entity.
   */
  public function setLabel($label);

  /**
   * Gets the Invite type type.
   *
   * @return string
   *   Type of the Invite type.
   */
  public function getType();

  /**
   * Sets the Invite type type.
   *
   * @param string $type
   *   The Invite type type.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite type entity.
   */
  public function setType($type);

  /**
   * Gets the Invite type creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Invite type.
   */
  public function getCreatedTime();

  /**
   * Sets the Invite type creation timestamp.
   *
   * @param int $timestamp
   *   The Invite type creation timestamp.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite type entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Invite type published status indicator.
   *
   * Unpublished Invite type are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Invite type is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Invite type.
   *
   * @param bool $published
   *   TRUE to set this Invite type to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite type entity.
   */
  public function setPublished($published);

}
