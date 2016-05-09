<?php

namespace Drupal\invite\Controller;

use Drupal\Core\Controller\ControllerBase;

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
  public function accept($reg_code) {
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Implement method: accept with parameter(s): $reg_code'),
    ];
  }

}
