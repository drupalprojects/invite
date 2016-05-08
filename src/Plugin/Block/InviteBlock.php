<?php

namespace Drupal\invite\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides a 'InviteBlock' block.
 *
 * @Block(
 *  id = "invite_block",
 *  admin_label = @Translation("Invite block"),
 *  deriver = "Drupal\invite\Plugin\Derivative\InviteBlock"
 * )
 */
class InviteBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $block_id = $this->getDerivativeId();
    $build = [];
    $build['invite_block']['#markup'] = 'Implement InviteBlock. id: ' . $block_id;

    return $build;
  }

}
