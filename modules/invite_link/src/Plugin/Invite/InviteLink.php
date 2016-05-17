<?php


namespace Drupal\invite_link\Plugin\Invite;


use Drupal\invite\InvitePluginInterface;

/**
 * @Plugin(
 *   id="invite_link",
 *   label = @Translation("Invite Link")
 * )
 */
class InviteLink implements InvitePluginInterface {
  public function send($invite) {
    // This plugin only generates a link.
  }
}