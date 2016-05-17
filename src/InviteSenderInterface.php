<?php

namespace Drupal\invite;

use Drupal\Core\Entity\ContentEntityInterface;
use Drupal\Core\Entity\EntityChangedInterface;
use Drupal\user\EntityOwnerInterface;

/**
 * Provides an interface for defining Invite sender entities.
 *
 * @ingroup invite
 */
interface InviteSenderInterface extends ContentEntityInterface, EntityChangedInterface, EntityOwnerInterface {
  /**
   * Gets the Invite Sender label.
   *
   * @return string
   *   Label of the Invite Sender.
   */
  public function label();

  /**
   * Sets the Invite Sender label.
   *
   * @param string $label
   *   The Invite Sender label.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite Sender entity.
   */
  public function setLabel($label);

  /**
   * Gets the Invite Sender type.
   *
   * @return string
   *   Type of the Invite Sender.
   */
  public function getType();

  /**
   * Sets the Invite Sender type.
   *
   * @param string $type
   *   The Invite Sender type.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite Sender entity.
   */
  public function setType($type);

  /**
   * Gets the Invite Sender creation timestamp.
   *
   * @return int
   *   Creation timestamp of the Invite Sender.
   */
  public function getCreatedTime();

  /**
   * Sets the Invite Sender creation timestamp.
   *
   * @param int $timestamp
   *   The Invite Sender creation timestamp.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite Sender entity.
   */
  public function setCreatedTime($timestamp);

  /**
   * Returns the Invite Sender published status indicator.
   *
   * Unpublished Invite Sender are only visible to restricted users.
   *
   * @return bool
   *   TRUE if the Invite Sender is published.
   */
  public function isPublished();

  /**
   * Sets the published status of a Invite Sender.
   *
   * @param bool $published
   *   TRUE to set this Invite Sender to published, FALSE to set it to unpublished.
   *
   * @return \Drupal\invite\InviteTypeInterface
   *   The called Invite Sender entity.
   */
  public function setPublished($published);

}
