<?php


namespace Drupal\invite_by_email\Plugin\Invite;


use Drupal\Core\Render\BubbleableMetadata;
use Drupal\invite\InvitePluginInterface;

/**
 * @Plugin(
 *   id="invite_by_email",
 *   label = @Translation("Invite By Email")
 * )
 */
class InviteByEmail implements InvitePluginInterface {

  /**
   * {@inheritdoc}
   */
  public function send($invite) {
    /*
     * @var $token \Drupal\token\Token
     * @var $mail \Drupal\Core\Mail\MailManager
     */
    $bubbleable_metadata = new BubbleableMetadata();
    $mail = \Drupal::service('plugin.manager.mail');
    $token = \Drupal::service('token');
    $mail_key = $invite->get('type')->value;
    $message = $mail->mail('invite_by_email', $mail_key, $invite->get('field_invite_email_address')->value, $invite->activeLangcode, array(), $invite->getOwner()
      ->getEmail());
    $message['subject'] = $token->replace($invite->get('field_invite_email_subject')->value, array('invite' => $invite), array(), $bubbleable_metadata);
    $message['body'] = $token->replace($invite->get('field_invite_email_body')->value, array('invite' => $invite), array(), $bubbleable_metadata);
    $system = $mail->getInstance(array(
      'module' => 'invite_by_email',
      'key' => $mail_key
    ));
    $system->mail($message);
  }

}