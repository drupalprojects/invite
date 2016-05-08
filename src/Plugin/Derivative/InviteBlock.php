<?php
/**
 * @file
 * Contains \Drupal\invite\Plugin\Derivative\InviteBlock.
 */
namespace Drupal\invite\Plugin\Derivative;

use Drupal\Component\Plugin\Derivative\DeriverInterface;
use Drupal\invite\Entity\InviteType;

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
   * {@inheritdoc}
   */
  public function getDerivativeDefinitions($base_plugin_definition) {
    $derivatives = array();
    $invite_types = InviteType::loadMultiple();
    foreach ($invite_types as $invite_type) {
      $type = $invite_type->getType();
      $derivatives[$type] = $base_plugin_definition;
      $derivatives[$type]['admin_label'] = $invite_type->label();
    }
    return $derivatives;
  }

}