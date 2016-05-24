<?php
/**
 * @file
 * Contains \Drupal\invite\Plugin\Derivative\InviteBlock.
 */
namespace Drupal\invite\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverInterface;
use Drupal\Core\Database\Database;

class InviteBlock implements DeriverInterface {

  /**
   * {@inheritdoc}
   */
  public function getDerivativeDefinition($derivative_id, $base_plugin_definition) {
    $derivatives = $this->getDerivativeDefinitions($base_plugin_definition);
    if (isset($derivatives[$derivative_id])) {
      return $derivatives[$derivative_id];
    }
  }

  /**
   * Creates a block for each sending method that is enabled on invite_types.
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $derivatives = array();
    $connection = Database::getConnection();
    foreach ($connection->query('SELECT * FROM invite_sender WHERE name=:provider', array(':provider' => $base_plugin_definition['provider']))->fetchAll() as $sending_method) {
      $derivatives[$sending_method->type] = $base_plugin_definition;
      $derivatives[$sending_method->type]['admin_label'] = \Drupal::config('invite.invite_type.' . $sending_method->type)->get('label');
    }

    return $derivatives;
  }

}