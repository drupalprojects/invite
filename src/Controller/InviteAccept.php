<?php

namespace Drupal\invite\Controller;

use Drupal\Component\EventDispatcher\ContainerAwareEventDispatcher;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Entity\Entity;
use Drupal\invite\InviteAcceptEvent;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class InviteAccept.
 *
 * @package Drupal\invite\Controller
 */
class InviteAccept extends ControllerBase {

  public $dispatcher;

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('event_dispatcher')
    );
  }

  public function __construct(ContainerAwareEventDispatcher $dispatcher) {
    $this->dispatcher = $dispatcher;
  }

  /**
   * Accept.
   *
   * @return string
   *   Return Hello string.
   */
  public function accept($invite) {
    $account = $this->currentUser();
    $redirect = '<front>';
    $message = '';
    $type = 'status';

    // Current user is the inviter.
    if ($account->id() == $invite->getOwnerId()) {
      $message = $this->t('You can not use your own invite...');
      $type = 'error';
      $redirect = '<front>';
    }
    else {
      $_SESSION['invite_code'] = $invite->getRegCode();
      $redirect = 'user.register';
    }

    $invite_accept = new InviteAcceptEvent(array(
      'redirect' => &$redirect,
      'message' => &$message,
      'type' => &$type,
      'invite' => $invite,
    ));

    $this->dispatcher->dispatch('invite_accept', $invite_accept);
    drupal_set_message($message, $type);

    return $this->redirect($redirect);
  }

}
