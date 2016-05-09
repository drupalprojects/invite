<?php

namespace Drupal\invite\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Entity;
use Drupal\invite\Entity\Invite;

/**
 * Class InviteAccept.
 *
 * @package Drupal\invite\Controller
 */
class InviteAccept extends ControllerBase {
  /**
   * Accept.
   *
   * @return string
   *   Return Hello string.
   */
  public function accept($invite) {

    // todo  lots of checks... user is not the inviter, invites valid, etc...
    $br = 1; // todo Remove!!!
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: accept with parameter(s): $reg_code'),
    ];
  }

}
