<?php
/**
 * Contains \Drupal\invite\InvitePluginInterface.
 */

namespace Drupal\invite;

/**
 * Developers can register an invite processing plugin that implements
 * InvitePluginInterface and annotates:
 *
 * @Plugin(
 *   id="Name of Invite plugin"
 * )
 *
 */
interface InvitePluginInterface {

  /**
   * Plugin send method.
   *
   * @param $invite
   *  The invite entity.
   */
  public function send($invite);
}